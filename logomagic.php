<?php

if (isset($_POST["submit"]))
{
        $SPACING = 15;
        $toplogo = $_FILES["toplogo"]["tmp_name"];
        $topname = $_FILES["toplogo"]["name"];
        $topsplit = split("\.", $topname);

        $bottomlogo = $_FILES["bottomlogo"]["tmp_name"];
        $bottomname = $_FILES["bottomlogo"]["name"];
        $bottomsplit = split("\.", $bottomname);
        list($topwidth, $topheight) = getimagesize($toplogo);
        list($bottomwidth, $bottomheight) = getimagesize($bottomlogo);
        $outputname = substr($topsplit[0], 0, 10)."-".substr($bottomsplit[0], 0, 10).".png";
        $topim = new imagick($toplogo);
        if ($topim->getImageAlphaChannel())
        {
                $topim->setImageBackgroundColor('white');
                $topim->setImageAlphaChannel(11);
                $topim->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        $bottomim = new imagick($bottomlogo);
        if ($bottomim->getImageAlphaChannel())
        {
                $bottomim->setImageBackgroundColor('white');
                $bottomim->setImageAlphaChannel(11);
                $bottomim->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        $output = new Imagick();
        if ($topwidth > $topheight)
        {
                if ($topwidth > $bottomwidth)
                {
                        $topim->resizeImage($bottomwidth,0,1,0);
                }
                else
                {
                        $bottomim->resizeImage($topwidth,0,1,0);
                }
                $output->newimage( $topim->getImageWidth(), $topim->getImageHeight() + $bottomim->getImageHeight() + $SPACING, "none");
                $output->compositeimage($topim->getimage(), Imagick::COMPOSITE_COPY, 0, 0);
                $output->compositeimage($bottomim->getimage(), Imagick::COMPOSITE_COPY, 0, $topim->getImageHeight() + $SPACING);
        }
        else
        {
                if ($topheight > $bottomheight)
                {
                        $topim->resizeImage(0,$bottomheight,1,0);
                }
                else
                {
                        $bottomim->resizeImage(0,$topheight,1,0);
                }
                $output->newimage( $topim->getImageWidth() + $bottomim->getImageWidth() + $SPACING, $topim->getImageHeight(), "none");
                $output->compositeimage($topim->getimage(), Imagick::COMPOSITE_COPY, 0, 0);
                $output->compositeimage($bottomim->getimage(), Imagick::COMPOSITE_COPY, $topim->getImageWidth() + $SPACING, 0);
        }
        header("Content-type: image/png");
        header("Content-Disposition: attachment; filename=\"$outputname\"");
        $output->setImageBackgroundColor('white');
        $output->setImageAlphaChannel(11);
        $output->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $output->setImageFormat('png32');
        echo $output->getImageBlob();

}
else
{
echo "<html>\n";
echo " <head><title>Logo Magic!</title></head>\n";
echo " <body>\n";
echo "  <form method=\"post\" name=\"logomagic\" enctype=\"multipart/form-data\">\n";
echo "   Top/Left Image: <input type=\"file\" name=\"toplogo\" id=\"toplogo\">\n";
echo "   <br><br>\n";
echo "   Bottom/Right Image: <input type=\"file\" name=\"bottomlogo\" id=\"bottomlogo\">\n";
        echo "   <br><br>\n";
        echo "   <input type=\"submit\" name=\"submit\" value=\"Make it happen!\">\n";
        echo "  </form>\n";
        echo " </body>\n";
        echo "</html>\n";
}

?>
