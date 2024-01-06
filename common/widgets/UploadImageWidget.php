<?php

namespace common\widgets;

use common\models\File;
use kartik\base\InputWidget;
use yii\helpers\Url;


/**
 * Preconfigured wideget for images upload.
 * Read documentation on http://demos.krajee.com/widget-details/fileinput
 */
class UploadImageWidget extends InputWidget
{
    /** @var File */
    public $file;

    public $options = [];

    public $pluginOptions = [];


    public function run()
    {
        if ($this->file instanceof File === false) {
            $this->file = null;
        }

        $this->options = array_merge($this->getDefaultOptions(), $this->options);
        $this->pluginOptions = array_merge($this->getDefaultPluginOptions(), $this->pluginOptions);

        return $this->render('upload-image', [
            'model' => $this->model,
            'file' => $this->file,
            'attribute' => $this->attribute,
            'options' => $this->options,
            'pluginOptions' => $this->pluginOptions,
        ]);
    }


    /**
     * It will be merged/override by user provided pluginOptions
     */
    protected function getDefaultPluginOptions(): array
    {
        return [
            'showCaption' => false,
            'showUpload' => false,
            'allowedFileTypes' => ['image'],
            'initialPreview' => [
                $this->file ? $this->file->getUrl() : null,
            ],
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'type' => 'image',
                    'filetype' => $this->file ? $this->file->type : null,
                    'url' => $this->file ? Url::toRoute(['/file/delete', 'id' => $this->file->file_id], true) : null,
                ],
            ],
            'deleteUrl' => $this->file ? Url::toRoute(['/file/delete', 'id' => $this->file->file_id], true) : null,
            'overwriteInitial' => false,
            'fileActionSettings' => [
                'showZoom' => false,
                'showDrag' => false,
            ],
        ];
    }

    protected function getDefaultOptions(): array
    {
        return [
            'accept' => 'image/*',
        ];
    }

}