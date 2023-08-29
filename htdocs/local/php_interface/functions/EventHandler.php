<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;

class EventHandler
{
    public static function init()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->addEventHandler("main", "OnEndBufferContent", [__CLASS__, "convertImage"]);
        $eventManager->addEventHandler("main", "OnEndBufferContent", [__CLASS__, "dropMaps"]);
    }

    public function dropMaps(&$content)
    {
        if (Helper::isPageSpeed()) {
            $content = str_replace('<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>', '', $content);
			$content = preg_replace('/(<script src="https:\/\/api-maps\.yandex\.ru\/[^"]*"><\/script>)/is', '', $content);
        }
    }

    public function convertImage(&$content)
    {
        if (defined('ADMIN_SECTION') || defined('WIZARD_SITE_ID')) {
            return;
        }

        preg_match_all( '/<([a-zA-Z]*)\s[^>]*["\'(](\/upload\/[^"]*\.(jpg|jpeg|png))["\')][^>]*>/i', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[2] as $i => $imageUrl) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $type = $matches[3][$i];
                $newName = str_replace($type, 'webp', $imageUrl);
                if (file_exists($root . $newName)) {
                    if ($matches[1][$i] == 'img') {
                        $content = str_replace($matches[0][$i], '<picture><source srcset="' . $newName . '" type="image/webp" />' . $matches[0][$i] . '</picture>', $content);
                    } else {
                        $content = str_replace($imageUrl, $newName, $content);
                    }
                    continue;
                }
                if (!file_exists($root . $imageUrl)) {
                    continue;
                }
                switch ($type) {
                    case 'jpeg':
                    case 'jpg':
                        $img = imagecreatefromjpeg($root . $imageUrl);
                        break;
                    case 'png':
                        $img = imagecreatefrompng($root . $imageUrl);
                        imagepalettetotruecolor($img);
                        imagealphablending($img, true);
                        imagesavealpha($img, true);
                        break;
                }
                if (isset($img)) {
                    $result = imagewebp($img, $root . $newName, 75);
                    if (!$result) {
                        imagedestroy($img);
                        unset($img);
                        continue;
                    }

                    if ($matches[1][$i] == 'img') {
                        $content = str_replace($matches[0][$i], '<picture><source srcset="' . $newName . '" type="image/webp" />' . $matches[0][$i] . '</picture>', $content);
                    } else {
                        $content = str_replace($imageUrl, $newName, $content);
                    }
                    imagedestroy($img);
                    unset($img);
                }
            }
        }
    }
}