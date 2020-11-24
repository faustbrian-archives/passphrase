<?php

declare(strict_types=1);

/*
 * This file is part of Passphrase.
 *
 * (c) Konceiver Oy <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\Passphrase;

final class EFF
{
    private array $wordList;

    private string $glue = '-';

    public static function new(): self
    {
        return new static();
    }

    public function useSeperator(): self
    {
        $this->wordList('eff_short_wordlist_1');

        return $this;
    }

    public function useFourDiceList(): self
    {
        $this->wordList('eff_short_wordlist_1');

        return $this;
    }

    public function useFiveDiceList(): self
    {
        $this->wordList('eff_large_wordlist');

        return $this;
    }

    public function useUniqueThreeCharacterList(): self
    {
        $this->wordList('eff_short_wordlist_2_0');

        return $this;
    }

    public function generate(int $count): string
    {
        $passphrase = [];
        $max        = count($this->wordList) - 1;

        foreach (range(1, $count) as $i) {
            $passphrase[] = $this->wordList[random_int(0, $max)];
        }

        return implode($this->glue, $passphrase);
    }

    private function wordList(string $wordList): void
    {
        $this->wordList = explode("\n", file_get_contents(realpath(__DIR__.'/eff/'.$wordList.'.txt')));
    }
}
