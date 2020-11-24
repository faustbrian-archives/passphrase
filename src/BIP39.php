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

final class BIP39
{
    private array $wordList;

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

    public function generate(int $wordCount): string
    {
        return collect($this->generateEntropy($wordCount))
            ->map(fn (string $chunk) => $this->wordList[bindec($chunk)])
            ->implode(' ');
    }

    private function wordList(string $wordList): void
    {
        $this->wordList = explode("\n", file_get_contents(realpath(__DIR__.'/bip39/'.$wordList.'.txt')));
    }

    public function generateEntropy(int $wordCount): array
    {
        $overallBits  = $wordCount * 11;
        $checksumBits = (($wordCount - 12) / 3) + 4;
        $entropyBits  = $overallBits - $checksumBits;
        $entropy      = bin2hex(random_bytes($entropyBits / 8));
        $checksum     = $this->checksum($entropy, $checksumBits);

        return str_split($this->hex2bits($entropy).$checksum, 11);
    }

    private function hex2bits(string $hex): string
    {
        $bits = '';

        for ($i = 0; $i < strlen($hex); $i++) {
            $bits .= str_pad(base_convert($hex[$i], 16, 2), 4, '0', STR_PAD_LEFT);
        }

        return $bits;
    }

    private function checksum(string $entropy, int $bits): string
    {
        $checksumChar = ord(hash('sha256', hex2bin($entropy), true)[0]);
        $checksum     = '';

        for ($i = 0; $i < $bits; $i++) {
            $checksum .= $checksumChar >> (7 - $i) & 1;
        }

        return $checksum;
    }
}
