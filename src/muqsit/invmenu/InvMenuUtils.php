<?php

namespace muqsit\invmenu;

use Closure;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\item\NameTag;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

final class InvMenuUtils {

    /**
     * @param InvMenu $menu
     * @param Item $item
     * @param ?int $size
     * @return InvMenu
     * showcase: https://media.discordapp.net/attachments/1060620088920772688/1354353020371996814/image-32.png?ex=67e6f54a&is=67e5a3ca&hm=6022d74ab088560b47293b7196e1a7f0e37b2be866d12e87787d5fd7d14d10dd&=&format=webp&quality=lossless&width=787&height=536
     */
    static public function doBorders(InvMenu $menu, Item $item, ?int $size = null): InvMenu   {
        $inventory = $menu->getInventory();

        $size ??= $inventory->getSize();

        for ($i = 0; $i < $size; $i++) {
            if ($i < 9 || $i >= $size - 9 || $i % 9 == 0 || $i % 9 == 8) {
                $inventory->setItem($i, $item);
            }
        }

        return $menu;
    }

    /**
     * @param string $name
     * @param mixed $tag
     * @return Closure
     */
    public static function onlyItemsWithTag(string $name, mixed $tag) : Closure{
        return static function(Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) use($name, $tag) : bool{
            return $itemClicked->getNamedTag()->getTag($name) !== null;
        };
    }


    public static function onlyObjects(object $target, string $class) :Closure {
        return static function (Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) use ($target, $class): bool {
            $baseName = $target::class;

            if (class_exists($baseName)) {
                if (is_a($class, $baseName, true)) {
                    $reflection = new \ReflectionClass($class);
                    return $reflection->isInstantiable();
                }
            }

            return false;
        };
    }

}
