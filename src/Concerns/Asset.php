<?php

namespace HelloNico\AcfSpreadsheet\Concerns;

trait Asset
{
    /**
     * Resolve an asset URI from Laravel Mix's manifest.
     *
     * @param  string $asset
     * @return string
     */
    public function asset($asset = null)
    {

        if (! file_exists($manifest = $this->path . '/assets/dist/manifest.json')) {
            return $this->uri . '/assets/dist/' . $asset;
        }

        $manifest = json_decode(file_get_contents($manifest), true);

        return $this->uri . '/assets/dist/' . ($manifest[$asset] ?? $asset);
    }
}
