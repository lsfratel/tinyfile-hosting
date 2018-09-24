<?php

namespace app\controllers;

use app\core\Upload;
use mako\config\Config;
use mako\http\response\builders\JSON;
use mako\http\response\senders\File;
use mako\http\response\senders\Redirect;
use mako\http\routing\Controller;
use mako\view\View;
use mako\view\ViewFactory;

class App extends Controller
{
    /**
     * Index route.
     *
     * @access  public
     * @param   \mako\view\ViewFactory $view
     * @return  \mako\view\View
     */
    public function welcome(ViewFactory $view): View
    {
        return $view->create('index', [
            'maxFileSize' => $this->config->get('uploads.max_file_size'),
            'humanizer' => $this->humanizer,
        ]);
    }

    /**
     * Upload route.
     *
     * @access  public
     * @param   \mako\view\ViewFactory $view
     * @return  \mako\http\response\builders\JSON
     */
    public function upload(ViewFactory $view): JSON
    {
        $file = $this->request->file('file');

        $result = Upload::handle($file, $this->config);
        if ($result["success"]) {
            $result["url"] = $this->urlBuilder->toRoute('show', ['id' => $result["id"]]);
            $result["delete"] = $this->urlBuilder->toRoute('show', ['id' => $result["delete_token"]]);
        }

        return $this->jsonResponse($result);
    }

    /**
     * Show route.
     *
     * @access  public
     * @param   \mako\view\ViewFactory $view
     * @param   string  $id The id generated for the file
     * @return  \mako\view\View
     */
    public function show(ViewFactory $view, string $id): View
    {
        if (strlen($id) > 10) {
            $file = Upload::delete($id);

            if (!$file) {
                $this->response->status(404);
                return $view->create('404');
            }

            $file->size = $this->humanizer->fileSize($file->size);

            return $view->create('delete', [
                "file" => $file,
                "maxFileSize" => $this->config->get('uploads.max_file_size'),
                "humanizer" => $this->humanizer,
            ]);
        }

        $result = Upload::get($id);

        if (!$result) {
            $this->response->status(404);
            return $view->create('404');
        }

        $result["file"]->size = $this->humanizer->fileSize($result["file"]->size);

        return $view->create('show', [
            "file" => $result["file"],
            "link" => $result["link"],
            "maxFileSize" => $this->config->get('uploads.max_file_size'),
            "humanizer" => $this->humanizer,
        ]);
    }

    /**
     * Delete uploaded file.
     *
     * @access  public
     * @param   \mako\view\ViewFactory $view
     * @param   string  $token The token generated for the file
     * @return  mako\http\response\senders\Redirect
     */
    public function delete(ViewFactory $view, string $token): Redirect
    {
        $file = Upload::delete($token);

        if ($file) {
            $this->fileSystem->remove(UPLOADS_PATH . $file->name);

            foreach ($file->links as $link) {
                $link->delete();
            }

            $file->delete();
        }

        return $this->redirectResponse('welcome');
    }

    /**
     * Download route.
     *
     * @access  public
     * @param   \mako\view\ViewFactory $view
     * @param   string  $token The token generated for the file
     * @return  \mako\view\View|\mako\http\response\senders\File
     */
    public function download(ViewFactory $view, string $token): object
    {
        $result = Upload::download($token);

        if (!$result) {
            $this->response->status(404);
            return $view->create('404');
        }

        return $this
            ->fileResponse(UPLOADS_PATH . $result->file->name)
            ->name($result->file->name_original)
            ->type($result->file->type)
            ->done(function () use ($result) {
                $result->file->downloads++;
                $result->file->save();
            });
    }
}
