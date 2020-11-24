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

use BadMethodCallException;

final class BIP39
{
    public static function new(): self
    {
        return new static();
    }

    public function useChineseSimplified(): self
    {
        $this->wordList('chinese_simplified');

        return $this;
    }

    public function useChineseTraditional(): self
    {
        $this->wordList('chinese_traditional');

        return $this;
    }

    public function useCzech(): self
    {
        $this->wordList('czech');

        return $this;
    }

    public function useEnglish(): self
    {
        $this->wordList('english');

        return $this;
    }

    public function useFrench(): self
    {
        $this->wordList('french');

        return $this;
    }

    public function useItalian(): self
    {
        $this->wordList('italian');

        return $this;
    }

    public function useJapanese(): self
    {
        $this->wordList('japanese');

        return $this;
    }

    public function useKorean(): self
    {
        $this->wordList('korean');

        return $this;
    }

    public function useSpanish(): self
    {
        $this->wordList('spanish');

        return $this;
    }

    public function generate(int $words): string
    {
        throw new BadMethodCallException('Not Implemented');
    }

    private function wordList(string $wordList): void
    {
        $this->wordList = explode("\n", file_get_contents(realpath(__DIR__.'/bip39/'.$wordList.'.txt')));
    }
}
