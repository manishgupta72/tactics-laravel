<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use File;
use Symfony\Component\Finder\Finder;

class CompressAllImages extends Command
{
    protected $signature = 'compress:allimages';
    protected $description = 'Compress all image files in a specific directory';

    public function handle()
    {
        set_time_limit(0);  // No time limit, let it run until it finishes
        ini_set('memory_limit', '512M');  // Increase memory limit

        $directory = public_path('/assets/images/member_images');
        $files = Finder::create()->files()->in($directory);

        $batchSize = 100;  // Processing 100 files at a time
        $fileChunks = array_chunk(iterator_to_array($files), $batchSize);

        foreach ($fileChunks as $chunk) {
            foreach ($chunk as $file) {
                $imagePath = $file->getRealPath();
                if (@getimagesize($imagePath)) {  // Check if file is an image
                    try {
                        $image = Image::make($imagePath);
                        $image->save($imagePath, 40);  // Compress and save with 40% quality
                        $this->info('Compressed: ' . $file->getFilename());
                    } catch (\Exception $e) {
                        $this->error('Failed to compress: ' . $file->getFilename());
                        $this->error('Error: ' . $e->getMessage());
                    } finally {
                        if (isset($image)) {
                            $image->destroy();  // Free up memory
                            unset($image);  // Ensure the variable is cleared
                        }
                    }
                } else {
                    $this->info('Skipped non-image file: ' . $file->getFilename());
                }
            }

            gc_collect_cycles();  // Force garbage collection after each batch
        }

        $this->info('All applicable image files have been compressed.');
    }
    
}

