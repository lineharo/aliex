<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\UrlMapTrait;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class Product extends Model
{
    use HasFactory;
    use UrlMapTrait;

    const IMAGES_PATH_PREFIX = 'products/';
    const IMAGES_THUMB_DIR = 'thumb/';
    const IMAGES_PATH_DETAIL = 'detail/';
    const IMAGES_PATH_PREVIEW = 'preview/';
    const IMAGE_SIZE = 1024;
    const IMAGE_SIZE_PREVIEW = 250;
    const IMAGE_SIZE_DETAIL = 400;

    public $imagesPath = '';

    protected $fillable = [
        'ali_id',
        'alicat_id',
        'ulid',
        'name',
        'description',
        'ali_description',
        'ali_properties',
        'ali_chars',
        'slug',
        'store_name',
        'store_url',
        'store_chat_url',
        'store_rating',
        'store_image',
        'price',
        'price_old',
        'rating',
        'reviews',
        'sales',
        'shows',
        'published',
        'status',
    ];

    public function category(): Array|Null
    {
        $res = null;
        foreach (config('__.aliCatIds') as $category) {
            if ($category['id'] == $this->alicat_id) {
                $res = $category;
            }
        }
        return $res;
    }

    // Images:
    /*
        'common' => [
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/0.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/1.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/2.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/3.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/4.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/5.jpg",
            "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/6.jpg"
        ],
        'spec' => [
            "collage" => "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/collage.jpg",
            "price" => "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/price.jpg"
        ],
        'web' => [
            'preview' => [
                "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/preview/0.jpg",
                "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/preview/1.jpg",
            ],
            'detail' => [
                "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/detail/0.jpg",
                "products/25/01/01JHAQHJ0424JRMSPP3ZD93G29/detail/1.jpg",
            ],
        ]
        'source' => [
            "//ae04.alicdn.com/kf/S4171079a2b064ab88e0be908d7866d32D.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/S360aa6a8f3ad4d069f61ef2c6e5279b7j.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/S3194e6d45e5944d2b1b23e904154a0f8T.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/S1fec1f481b3a4141830270fdfff7027cg.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/S2bec51e32a6641bc80ab2e5fd445d6efz.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/Sf9373fed037c4e359135319e15d74564A.jpg_480x480.jpg",
            "//ae04.alicdn.com/kf/S352e23af4c3642898cbf758ddb5ed95du.jpg_480x480.jpg"
        ]
    */

    // Скачать изображения
    public function downloadImages($images, $path)
    {
        $disk = Storage::disk('images');
        $dir = Product::IMAGES_PATH_PREFIX . $path;

        if (!$disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $count = 0;

        foreach ($images as $index => $image) {
            $count++;
            // Удалить суффиксы "_480x480q55.jpg" и "_480x480.jpg"
            $url = preg_replace('/_480x480(q55)?\.jpg$/', '', $image);

            try {
                if (strpos($url, 'https:') === false) {
                    $url = 'https:' . $url;
                }
                $file = file_get_contents($url);
                if ($file === false) {
                    continue;
                }

                $filename = $dir . $index . '.jpg';
                $imgPath = $disk->path($filename);

                file_put_contents($imgPath, $file);

            } catch(ErrorException $e) {
                continue;
            }

            if ($count >= 8) break;

        }

    }

    public function prepareImages()
    {
        $disk = Storage::disk('images');
        $imagePath = self::IMAGES_PATH_PREFIX . $this->imagesPath;  // путь к изображениям товара
        $imageDetailPath = $imagePath . self::IMAGES_PATH_DETAIL;   // путь к превью изображениям товара
        $imagePreviewPath = $imagePath . self::IMAGES_PATH_PREVIEW; // путь к детальным изображениям товара

        $files = $disk->files($imagePath);

        if (empty($files)) return null;

        $res = [
            'sources' => [],
            'common' => [],
            'spec' => [],
            'web' => [
                'preview' => [],
                'detail' => []
            ],
        ];

        foreach ($files as $index => $file) {
            $path = $disk->path($file);
            $fileName = pathinfo($path, PATHINFO_FILENAME) . '.webp';
            $imagick = new Imagick($path);

            if ($imagick->getImageWidth() > self::IMAGE_SIZE || $imagick->getImageHeight() > self::IMAGE_SIZE) {
                $imagick->scaleImage(self::IMAGE_SIZE, self::IMAGE_SIZE, true);
            }

            // Наложение ватермарки
            $watermark = new Imagick(Storage::disk('images')->path('/products/watermark.png'));
            $watermark_left = 100;
            $watermark_top = min($imagick->getImageHeight(), self::IMAGE_SIZE) - $watermark->getImageHeight() - 100;
            $imagick->compositeImage($watermark, Imagick::COMPOSITE_OVER, $watermark_left, $watermark_top);

            // Добавление серой рамки в 2 пикселя
            $draw = new ImagickDraw();
            $draw->setStrokeColor(new ImagickPixel('#C9C9C9'));
            $draw->setStrokeWidth(2);
            $draw->setFillColor('none');
            $draw->rectangle(1, 1, $imagick->getImageWidth() - 2, $imagick->getImageHeight() - 2);
            $imagick->drawImage($draw);

            $imagick->setImageFormat('webp'); // Установить формат WebP
            $imagick->setImageCompressionQuality(80); // Качество 80 (оптимальный баланс)
            $imagick->writeImage($path);

            $res['common'][] = $file;

            if ($index >= 2) continue;

            // Уменьшить для превью
            if (!$disk->exists($imagePreviewPath)) {
                $disk->makeDirectory($imagePreviewPath);
            }
            $previewFile = $imagePreviewPath . $fileName;
            $imagick = new Imagick($path);
            $imagick->scaleImage(self::IMAGE_SIZE_PREVIEW, self::IMAGE_SIZE_PREVIEW, true);
            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $canvas = new Imagick();
            $canvas->newImage(self::IMAGE_SIZE_PREVIEW, self::IMAGE_SIZE_PREVIEW, new ImagickPixel('white'));
            $x = (self::IMAGE_SIZE_PREVIEW - $width) / 2;
            $y = (self::IMAGE_SIZE_PREVIEW - $height) / 2;
            $canvas->compositeImage($imagick, Imagick::COMPOSITE_OVER, $x, $y);
            $canvas->setImageFormat('webp'); // Установить формат WebP
            $canvas->setImageCompressionQuality(80); // Качество 80
            $canvas->writeImage($disk->path($previewFile));
            $res['web']['preview'][] = $previewFile;

            // Уменьшить для детальных
            if (!$disk->exists($imageDetailPath)) {
                $disk->makeDirectory($imageDetailPath);
            }
            $detailFile = $imageDetailPath . $fileName;
            $imagick = new Imagick($path);
            $imagick->scaleImage(self::IMAGE_SIZE_DETAIL, self::IMAGE_SIZE_DETAIL, true);
            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $canvas = new Imagick();
            $canvas->newImage(self::IMAGE_SIZE_DETAIL, self::IMAGE_SIZE_DETAIL, new ImagickPixel('white'));
            $x = (self::IMAGE_SIZE_DETAIL - $width) / 2;
            $y = (self::IMAGE_SIZE_DETAIL - $height) / 2;
            $canvas->compositeImage($imagick, Imagick::COMPOSITE_OVER, $x, $y);
            $canvas->setImageFormat('webp'); // Установить формат WebP
            $canvas->setImageCompressionQuality(80); // Качество 80
            $canvas->writeImage($disk->path($detailFile));
            $res['web']['detail'][] = $detailFile;
        }

        // Цену на первую фотографию
        $firstImage = array_shift($files);
        $this->processFirstImage($firstImage, round($this->price / 100), round($this->price_old / 100));
        $res['spec']['price'] = $imagePath . 'price.jpg';

        // Коллаж фотографий для ТГ
        $this->createCollage($imagePath);
        $res['spec']['collage'] = $imagePath . 'collage.jpg';

        return $res;
    }

    // Добавить цены на изображение
    private function processFirstImage($image, $price, $oldPrice)
    {
        $offsetLeft = 40;
        $offsetTop = 40;
        $path = Storage::disk('images')->path($image);

        $handle = fopen($path, 'rb');
        $img = new Imagick();
        $img->readImageFile($handle);

        $drawOldPrice = new ImagickDraw();
        $drawNewPrice = new ImagickDraw();

        $drawOldPrice->setFont(storage_path('modules/images/Montserrat-Regular.ttf'));
        $drawNewPrice->setFont(storage_path('modules/images/Montserrat-Bold.ttf'));

        $drawNewPrice->setFontSize(40);
        $drawOldPrice->setFontSize(25);

        $drawNewPrice->setTextAlignment(Imagick::ALIGN_CENTER);
        $drawOldPrice->setTextAlignment(Imagick::ALIGN_CENTER);

        $textMetric = ($img->queryFontMetrics($drawNewPrice, $price));
        $newPriceWidth = $textMetric['textWidth'] + 60;
        $newPriceHeight = $textMetric['characterHeight'] + $textMetric['descender'] + 30;
        $drawNewPrice->setFillColor('#000000');
        $drawNewPrice->roundRectangle(
            $offsetLeft + 2,
            $offsetTop + 2,
            $offsetLeft + $newPriceWidth + 2,
            $offsetTop + $newPriceHeight + 2,
            10, 10
        );
        $drawNewPrice->setFillColor('#B61732');
        $drawNewPrice->roundRectangle(
            $offsetLeft,
            $offsetTop,
            $offsetLeft + $newPriceWidth,
            $offsetTop + $newPriceHeight,
            10, 10
        );

        $textMetric = ($img->queryFontMetrics($drawOldPrice, $oldPrice));
        $oldPriceWidth = $textMetric['textWidth'] + 30;
        $oldPriceHeight = $textMetric['characterHeight'] + $textMetric['descender'] + 20;
        $drawOldPrice->setFillColor('#ffffff');
        $left = $offsetLeft + (($newPriceWidth - $oldPriceWidth) / 2);
        $top = $offsetTop + $newPriceHeight;
        $drawOldPrice->roundRectangle(
            $left,
            $top - 10,
            $left + $oldPriceWidth,
            $top + $oldPriceHeight,
            10, 10
        );


        $img->drawImage($drawOldPrice);
        $img->drawImage($drawNewPrice);


        $drawNewPrice->setFillColor('#ffffff');
        $img->annotateImage(
            $drawNewPrice,
            $offsetLeft + $newPriceWidth/2,
            $offsetTop + $newPriceHeight - 13,
            0,
            $price . ' ₽'
        );

        $drawOldPrice->setFillColor('#999999');
        $img->annotateImage(
            $drawOldPrice,
            $left + $oldPriceWidth / 2,
            $top + $oldPriceHeight - 10,
            0,
            $oldPrice . ' ₽'
        );

        $draw = new ImagickDraw();
        $draw->setStrokeWidth(2);
        $draw->setStrokeColor('#999999');
        $draw->line(
            $left + 8,
            $top + $oldPriceHeight - 8,
            $left + $oldPriceWidth - 8,
            $top + 8
        );
        $img->drawImage($draw);

        $img->setCompression(Imagick::COMPRESSION_JPEG);
        $img->setCompressionQuality(100);

        $path = pathinfo($path);

        $img->writeImage($path['dirname'] . '/' . 'price.' . $path['extension']);
    }

    private function createCollage($path)
    {
        $files = Storage::disk('images')->files($path);
        rsort($files);

        $num_images = count($files);
        $cols = ceil(sqrt($num_images));
        $rows = ceil($num_images / $cols);

        $result_image = new Imagick();
        $canvas_width = $canvas_height = null;

        $images = [];
        $maxWidth = 0;
        $maxHeight = 0;
        // Проходимся по каждой ячейке коллажа и добавляем фотографии
        foreach ($files as $index => $image_path) {
            $images[$index] = new Imagick(Storage::disk('images')->path($image_path));

            // Определяем размер каждого квадрата
            $square_width = $images[$index]->getImageWidth();
            $square_height = $images[$index]->getImageHeight();

            $maxWidth = max($square_width, $maxWidth);
            $maxHeight = max($square_height, $maxHeight);
        }

        if ($maxWidth > 1024) $maxWidth = 1024;
        if ($maxHeight > 1024) $maxHeight = 1024;
        $canvas_width = $cols * $maxWidth;
        $canvas_height = $rows * $maxHeight;
        $result_image->newImage($canvas_width, $canvas_height, "white");

        foreach ($images as $index => $image) {
            // Определяем координаты для текущей ячейки
            $x = ($index % $cols) * $maxWidth;
            $y = floor($index / $cols) * $maxHeight;

            // Добавляем фотографию в текущую ячейку
            $result_image->compositeImage($image, Imagick::COMPOSITE_DEFAULT, $x, $y);

            // Очищаем ресурсы
            $image->clear();
        }

        // Если есть пустое место, то добавить изображение-заглушку
        if ($index < $num_images) {
            $index++;
            $image = new Imagick(Storage::disk('images')->path('/products/blank1024.png'));
            $image->scaleImage($maxWidth, $maxHeight, true);
            $x = ($index % $cols) * $maxWidth;
            $y = floor($index / $cols) * $maxHeight;
            $result_image->compositeImage($image, Imagick::COMPOSITE_DEFAULT, $x, $y);
            $image->clear();
        }

        $result_image->scaleimage(2200, 2200);

        // Сохраняем результат
        $result_image_path = Storage::disk('images')->path($path . 'collage.jpg');
        $result_image->writeImage($result_image_path);

        // Освобождаем ресурсы
        $result_image->clear();
    }

    public function getPreviewImages()
    {
        $images = json_decode($this->images, true);
        return $images['web']['preview'] ?? [];
    }

    public function getDetailImages()
    {
        $images = json_decode($this->images, true);
        return $images['web']['detail'] ?? [];
    }

    /*
    public function getPreviewImages(int $count = 10, array $size = null): Array
    {
        $images = json_decode($this->images, true);
        return $images['common'] ?? [];
        /*
        $publicPath = self::IMAGES_PATH_PREFIX . $this->images;
        $files = Storage::disk('images')->files($publicPath);

        $files = array_slice($files, 0, $count);

        if (count($files) == 0) {
            return ['products/blank.png'];
        }

        $res = [];
        if ($size) {
            foreach($files as $file) {
                $thumb = self::getThumbImage($publicPath, $file, $size);
                if (is_null($thumb)) continue;
                $res[] = $thumb;
            };
            $files = $res;
        }

        return $files;* /
    }
    */

    public function getImages(array $size = null)
    {
        $images = json_decode($this->images, true);
        return $images['common'] ?? [];
        /*
        $publicPath = self::IMAGES_PATH_PREFIX . $this->images;
        $files = Storage::disk('images')->files($publicPath);

        if (count($files) == 0) {
            return ['products/blank.png'];
        }

        $res = [];
        if ($size) {
            foreach($files as $file) {
                $thumb = self::getThumbImage($publicPath, $file, $size);
                if (is_null($thumb)) continue;
                $res[] = $thumb;
            };
            $files = $res;
        }

        return $res;
        */
    }

    public function getSocFilesImages()
    {
        $images = json_decode($this->images, true);

        if (!isset($images['common']) || !is_array($images['common'])) {
            return [];
        }

        // Извлекаем изображения из `common` без первого элемента
        $commonImages = array_slice($images['common'], 1);

        // Добавляем изображение `spec.price`, если оно существует
        if (isset($images['spec']['price'])) {
            array_unshift($commonImages, $images['spec']['price']);
        }

        // Ограничиваем массив до первых 9 элементов
        return array_slice($commonImages, 0, 9);
    }

    public function getDiscount()
    {
        if ($this->price > 0 && $this->price_old) {
            return 100 - round($this->price / $this->price_old * 100);
        }
    }

    /*
    private static function getThumbImage($publicPath, $file, $size)
    {
        $filePathInfo = pathinfo(Storage::disk('images')->path($file));

        $filePath = $filePathInfo['dirname'] . '/' . self::IMAGES_THUMB_DIR;
        $fileName = $filePathInfo['filename'];
        $fileExt = $filePathInfo['extension'];

        if ($fileName == 'soc') return null;

        if (!\File::exists($publicPath . self::IMAGES_THUMB_DIR)) {
            Storage::disk('images')->makeDirectory($publicPath . self::IMAGES_THUMB_DIR);
        }

        $fileThumb = $fileName . '__' . $size['width'] . 'x' . $size['height'] . '.' . $fileExt;

        if (!\File::exists($filePath . $fileThumb)) {
            $imagick = new \Imagick(Storage::disk('images')->path($file));
            $imagick->scaleimage($size['width'], $size['height']);
            $imagick->writeImage($filePath . $fileThumb);
        }
        return $publicPath . self::IMAGES_THUMB_DIR . $fileThumb;
    }
    */

    public function isExtraImages()
    {
        $images = json_decode($this->images, true);

        if (isset($images['common']) && count($images['common']) > 0) return true;

        if (isset($images['spec']['collage']) && !is_null($images['spec']['collage'])) return true;
        if (isset($images['spec']['price']) && !is_null($images['spec']['price'])) return true;

        return false;
    }

    public function removeImages()
    {
        $images = json_decode($this->images, true);

        if (!isset($images['common']) || !is_array($images['common'])) {
            return [];
        }

        $commonImages = $images['common'];
        Storage::disk('images')->delete($commonImages);
        $images['common'] = [];

        if (isset($images['spec']['price'])) {
            Storage::drive('images')->delete($images['spec']['price']);
            $images['spec']['price'] = null;
        }

        if (isset($images['spec']['collage'])) {
            Storage::drive('images')->delete($images['spec']['collage']);
            $images['spec']['collage'] = null;
        }

        $this->images = json_encode($images);
        $this->timestamps = false;
        $this->save();
    }
}
