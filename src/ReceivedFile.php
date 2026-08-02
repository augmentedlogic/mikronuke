<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class ReceivedFile
{
    private ?int $size = 0;
    private ?string $tmp_name = null;
    private ?string $filetype = null;
    private ?string $name = null;
    private bool $valid = false;

    public function __construct($file_props)
    {
        if (isset($file_props['tmp_name'])) {
            $this->tmp_name = $file_props['tmp_name'];
            $this->size = $file_props['size'];
            $this->name = $file_props['name'];
            $this->filetype = $file_props['type'];
            $this->valid = true;
        }
    }

    public function getTmpName(): ?string
    {
        return $this->tmp_name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getType(): ?string
    {
        return $this->filetype;
    }

    public function getMimetype(): ?string
    {
        return $this->filetype;
    }

    public function valid(): bool
    {
        return $this->valid;
    }

    public function saveTo($targetdir, $name = null): void
    {
        if (!$name) {
            $name = $this->name;
        }
        move_uploaded_file($this->tmp_name, $targetdir . '/' . $name);
    }
}
