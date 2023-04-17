<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    protected $s3;

    public function __construct(S3Client $s3)
    {
        $this->s3 = $s3;
    }

    public function upload(UploadedFile $file, $path = null)
    {
        $path = $path ?? 'uploads';

        $fileName = $file->getClientOriginalName();

        $this->s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $path . '/' . $fileName,
            'Body' => fopen($file, 'r'),
            'ACL' => 'public-read'
        ]);

        return $fileName;
    }

    public function update($oldFileName, UploadedFile $newFile, $path = null)
    {
        $path = $path ?? 'uploads';

        $this->delete($oldFileName, $path);

        return $this->upload($newFile, $path);
    }

    public function delete($fileName, $path = null)
    {
        $path = $path ?? 'uploads';

        $this->s3->deleteObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $path . '/' . $fileName
        ]);
    }
}
