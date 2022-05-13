<?php

namespace App\Helpers;

use Askedio\Laravel5ProfanityFilter\ProfanityFilter as BaseFilter;

class ProfanityFilter extends BaseFilter
{
    public function noProfanity($string)
    {
        $this->resetFiltered();

        if (!is_string($string) || !trim($string)) {
            return;
        }

        $filtered = $this->filterString($string);

        foreach ($this->badWords as $badword) {
            try {
                if (preg_match("/$badword/iu", $string, $matches, PREG_UNMATCHED_AS_NULL) == 1) {
                    return preg_replace('/[^a-z_\-0-9]/i', '', $badword);
                }
            } catch (\Exception $e) {
                \Log::warning("$badword is not a valid badword expression");
            }
        }

        return true;
    }

    private function setFiltered($string)
    {
        array_push($this->filteredStrings, $string);

        if (!$this->wasFiltered) {
            $this->wasFiltered = true;
        }
    }

    private function resetFiltered()
    {
        $this->filteredStrings = [];

        $this->wasFiltered = false;
    }

    private function filterString($string)
    {
        return preg_replace_callback($this->filterChecks, function ($matches) {
            return $this->replaceWithFilter($matches[0]);
        }, $string);
    }

    private function replaceWithFilter($string)
    {
        $this->setFiltered($string);

        $strlen = mb_strlen($string);

        if ($this->multiCharReplace) {
            return str_repeat($this->replaceWith, $strlen);
        }

        return $this->randomFilterChar($strlen);
    }

    private function generateFilterChecks()
    {
        $this->filterChecks = [];

        foreach ($this->badWords as $string) {
            $this->filterChecks[] = $this->getFilterRegexp($string);
        }
    }

    private function getFilterRegexp($string)
    {
        $replaceFilter = $this->replaceFilter($string);

        if ($this->replaceFullWords) {
            return '/\b' . $replaceFilter . '\b/iu';
        }

        return '/' . $replaceFilter . '/iu';
    }

    private function replaceFilter($string)
    {
        return str_ireplace(array_keys($this->strReplace), array_values($this->strReplace), $string);
    }

    private function randomFilterChar($len)
    {
        return str_shuffle(str_repeat($this->replaceWith, intval($len / $this->replaceWithLength)) . substr($this->replaceWith, 0, ($len % $this->replaceWithLength)));
    }
}