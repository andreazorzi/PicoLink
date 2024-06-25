<?php

namespace App\Http\Controllers;

use App\Models\Short;
use Illuminate\Http\Request;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCodeException;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\Output\{QRGdImagePNG, QRCodeOutputException};

class ImageController extends Controller
{
    public static function toBase64(string $url):string{
        return config("app.env") == "local" ? "data:image/png;base64,".base64_encode(Storage::disk('asset')->get($url)) : asset($url);
    }
    
    public function qrcode(Request $request, Short $short, $logo = null){
        $options = new QROptions;
	    $options->quietzoneSize = 0;
        $options->outputBase64 = false;
        
        // // PNG LOGO
        // $logo = public_path('images/favicon.png');
        // if(!is_null($logo)){
        //     $options->version = 5;
        //     $options->scale = 6;
        //     $options->imageTransparent = false;
        //     $options->keepAsSquare = [
        //         QRMatrix::M_FINDER,
        //         QRMatrix::M_FINDER_DOT,
        //     ];
        //     $options->logoSpaceWidth = 13;
        //     $options->logoSpaceHeight = 13;
        //     $options->eccLevel = EccLevel::H;
        //     $options->addLogoSpace = true;
            
        //     header('Content-type: image/png');
            
        //     $qrcode = new QRCode($options);
        //     $qrcode->addByteSegment($short->getLink());
        //     $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());
        //     echo $qrOutputInterface->dump(null, $logo);
        // }
        
        if(!is_null($logo)){
            $options = new SVGWithLogoOptions;
            $options->quietzoneSize = 0;
            $options->outputBase64        = false;
            $options->svgLogo             = $logo;
            $options->svgLogoScale        = 0.5;
            $options->svgLogoCssClass     = 'dark';
            // QROptions
            // $options->version             = 5;
            $options->outputType          = QROutputInterface::CUSTOM;
            $options->outputInterface     = QRSvgWithLogo::class;
            $options->eccLevel            = EccLevel::H; // ECC level H is necessary when using logos
            $options->drawLightModules    = false;
            $options->addLogoSpace = true;
            $options->logoSpaceWidth = 10;
            $options->logoSpaceHeight = 10;
            $options->keepAsSquare        = [
                QRMatrix::M_FINDER_DARK,
                QRMatrix::M_FINDER_DOT,
                QRMatrix::M_ALIGNMENT_DARK,
            ];
        }
        
        $options->connectPaths = true;
        // https://developer.mozilla.org/en-US/docs/Web/SVG/Element/linearGradient
        // $options->svgDefs = '
        //     <linearGradient id="gradient" x1="100%" y2="100%">
        //         <stop stop-color="#D70071" offset="0"/>
        //         <stop stop-color="#9C4E97" offset="0.5"/>
        //         <stop stop-color="#0035A9" offset="1"/>
        //     </linearGradient>
        //     <style><![CDATA[
        //         .dark{fill: url(#gradient);}
        //     ]]></style>
        // ';
        
        header('Content-type: image/svg+xml');
            
        $qrcode = new QRCode($options);
        echo $qrcode->render($short->getLink());
    }
}

class QRImageWithLogo extends QRGdImagePNG{

    /**
     * @param string|null $file
     * @param string|null $logo
     *
     * @return string
     * @throws \chillerlan\QRCode\Output\QRCodeOutputException
     */
    public function dump(string $file = null, string $logo = null):string{
        // set returnResource to true to skip further processing for now
        $this->options->returnResource = true;

        // of course, you could accept other formats too (such as resource or Imagick)
        // I'm not checking for the file type either for simplicity reasons (assuming PNG)
        if(!is_file($logo) || !is_readable($logo)){
            throw new QRCodeOutputException('invalid logo');
        }

        // there's no need to save the result of dump() into $this->image here
        parent::dump($file);

        $im = imagecreatefrompng($logo);

        // get logo image size
        $w = imagesx($im);
        $h = imagesy($im);

        // set new logo size, leave a border of 1 module (no proportional resize/centering)
        $lw = (($this->options->logoSpaceWidth - 2) * $this->options->scale);
        $lh = (($this->options->logoSpaceHeight - 2) * $this->options->scale);

        // get the qrcode size
        $ql = ($this->matrix->getSize() * $this->options->scale);

        // scale the logo and copy it over. done!
        imagecopyresampled($this->image, $im, (($ql - $lw) / 2), (($ql - $lh) / 2), 0, 0, $lw, $lh, $w, $h);

        $imageData = $this->dumpImage();

        $this->saveToFile($imageData, $file);

        if($this->options->outputBase64){
            $imageData = $this->toBase64DataURI($imageData);
        }

        return $imageData;
    }

}

class QRSvgWithLogo extends QRMarkupSVG{

	/**
	 * @inheritDoc
	 */
	protected function paths():string{
		$size = (int)ceil($this->moduleCount * $this->options->svgLogoScale);

		// we're calling QRMatrix::setLogoSpace() manually, so QROptions::$addLogoSpace has no effect here
		$this->matrix->setLogoSpace($size, $size);

		$svg = parent::paths();
		$svg .= $this->getLogo();

		return $svg;
	}

	/**
	 * @inheritDoc
	 */
	protected function path(string $path, int $M_TYPE):string{
		// omit the "fill" and "opacity" attributes on the path element
		return sprintf('<path class="%s" d="%s"/>', $this->getCssClass($M_TYPE), $path);
	}

	/**
	 * returns a <g> element that contains the SVG logo and positions it properly within the QR Code
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Element/g
	 * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/transform
	 */
	protected function getLogo():string{
		// @todo: customize the <g> element to your liking (css class, style...)
		return sprintf(
			'%5$s<g transform="translate(%1$s %1$s) scale(%2$s)" class="%3$s">%5$s	%4$s%5$s</g>',
			(($this->moduleCount - ($this->moduleCount * $this->options->svgLogoScale)) / 2),
			$this->options->svgLogoScale,
			$this->options->svgLogoCssClass,
			file_get_contents($this->options->svgLogo),
			$this->options->eol
		);
	}

}


/**
 * augment the QROptions class
 */
class SVGWithLogoOptions extends QROptions{
	// path to svg logo
	protected string $svgLogo;
	// logo scale in % of QR Code size, clamped to 10%-30%
	protected float $svgLogoScale = 0.20;
	// css class for the logo (defined in $svgDefs)
	protected string $svgLogoCssClass = '';

	// check logo
	protected function set_svgLogo(string $svgLogo):void{

		if(!file_exists($svgLogo) || !is_readable($svgLogo)){
			throw new QRCodeException('invalid svg logo');
		}

		// @todo: validate svg

		$this->svgLogo = $svgLogo;
	}

	// clamp logo scale
	protected function set_svgLogoScale(float $svgLogoScale):void{
		$this->svgLogoScale = max(0.05, min(0.3, $svgLogoScale));
	}

}