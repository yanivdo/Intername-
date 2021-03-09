<?php
class View
{

    protected function createTemplate($path, $data)
    {
        $file = file_get_contents($path);
        foreach ($data as $keyName => $singleData) {

            $file = str_replace('{{' . $keyName . '}}', $singleData, $file);
        }
        $file = preg_replace('/{{\w*}}/s', '', $file);
        return $file;
    }
    protected function attachToIndex($htmlFile)
    {
        $page = $this->createTemplate('./template/index.html', [
            'body' => $htmlFile,
        ]);
        return $page;
    }
}