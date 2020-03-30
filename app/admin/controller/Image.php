<?php

namespace app\admin\controller;

use think\facade\Filesystem;

class Image extends AdminBase
{
    public function upload()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求不合法');
        }
        $file = $this->request->file('file');
        // 默认上传路径
        $filename = Filesystem::putFile('upload', $file);
        // 自定义上传路径
        $filename = Filesystem::disk('public')->putFile('upload/image', $file);
        if (!$filename) {
            return show(config('status.error'), '文件上传失败');
        }
        $imageUrl = [
            'image' => '/storage/' . $filename
        ];
        return show(config('status.success'), '文件上传成功', $imageUrl);
    }

    public function layUpload()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求不合法');
        }
        $result = [
            'code' => 1,
            'data' => []
        ];
        $file = $this->request->file('file');
        $filename = Filesystem::disk('public')->putFile('upload/image', $file);
        if (!$filename) {
            return json(['code' => 1, 'data' => []], 200);
        }
        $result = [
            'code' => 0,
            'data' => [
                'src' => '/storage/' . $filename
            ]
        ];
        return json($result, 200);
    }
}