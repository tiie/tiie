<?php
namespace Tiie\OpenGraph;

class Preparator
{
    private $objects = array();
    private $typeToPropertyMap = array(
        "title" => "title",
        "description" => "description",
        "type" => "type",
        "url" => "url",
        "siteName" => "site_name",
        "image" => "image",
        "audio" => "audio",
        "video" => "video",
        "locale" => "locale",
        "localeAlternate" => "locale:alternate",
    );

    public function title(string $title)
    {
        $this->objects[] = array(
            "type" => "title",
            "value" => $title,
        );

        return $this;
    }

    public function description(string $description)
    {
        $this->objects[] = array(
            "type" => "description",
            "value" => $description,
        );

        return $this;
    }

    public function type(string $type)
    {
        $this->objects[] = array(
            "type" => "type",
            "value" => $type,
        );

        return $this;
    }

    public function url(string $url)
    {
        $this->objects[] = array(
            "type" => "url",
            "value" => $url,
        );

        return $this;
    }

    public function siteName(string $siteName)
    {
        $this->objects[] = array(
            "type" => "siteName",
            "value" => $siteName,
        );

        return $this;
    }

    public function addImage(array $image)
    {
        $this->objects[] = array(
            "type" => "image",
            "value" => $image,
        );

        return $this;
    }

    public function addAudio(array $audio)
    {
        $this->objects[] = array(
            "type" => "audio",
            "value" => $audio,
        );

        return $this;
    }

    public function addVideo(array $video)
    {
        $this->objects[] = array(
            "type" => "video",
            "value" => $video,
        );

        return $this;
    }

    public function locale(string $locale)
    {
        $this->objects[] = array(
            "type" => "locale",
            "value" => $locale,
        );

        return $this;
    }

    public function addLocaleAlternate(string $locale)
    {
        $this->objects[] = array(
            "type" => "localeAlternate",
            "value" => $locale,
        );

        return $this;
    }

    public function prepare()
    {
        $prepared = "";

        foreach($this->objects as $object) {

            if(in_array($object["type"], array(
                "title",
                "description",
                "type",
                "url",
                "siteName",
                "locale",
                "localeAlternate",
            ))) {
                $prepared .= "<meta property=\"og:{$this->typeToPropertyMap[$object["type"]]}\" content=\"{$object["value"]}\"/>\n";
            } else if(in_array($object["type"], array(
                "image",
                "audio",
                "video",
            ))) {
                $value = $object["value"];

                if (!empty($value["value"])) {
                    $prepared .= "<meta property=\"og:{$this->typeToPropertyMap[$object["type"]]}\" content=\"{$value["value"]}\"/>\n";
                }

                foreach($value as $key => $v) {
                    if ($key == "value") {
                        continue;
                    }

                    $prepared .= "<meta property=\"og:{$this->typeToPropertyMap[$object["type"]]}{$key}\" content=\"{$v}\"/>\n";
                }
            }
        }

        return empty($prepared) ? null : $prepared;
    }
}
