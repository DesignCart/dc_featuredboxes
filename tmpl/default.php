<?php

/**
 * @package     DC Featured Boxes (mod_dc_featuredboxes)
 * @copyright   Copyright (C) 2026. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$boxes = $boxes ?? [];
if (empty($boxes)) {
	return;
}

// Helper: get field from box (array or object, incl. Registry-style)
$getBoxValue = function ($box, $key, $default = '') {
	if (is_array($box)) {
		return $box[$key] ?? $default;
	}
	if (is_object($box)) {
		if ($box instanceof \Joomla\Registry\Registry) {
			return $box->get($key, $default);
		}
		return $box->$key ?? $default;
	}
	return $default;
};

$modId = 'dc-featuredboxes-' . $module->id;

$columns           = (int) $params->get('columns', 4);
$everySecondLower  = (bool) $params->get('every_second_lower', 0);
$showImage         = (bool) $params->get('show_image', 1);
$imageMaxWidth     = (int) $params->get('image_max_width', 100);
$imageBorder       = (bool) $params->get('image_border', 0);
$imageBorderWidth  = (int) $params->get('image_border_width', 2);
$imageBorderColor  = $params->get('image_border_color', '#ffffff');
$imageLinked       = (bool) $params->get('image_linked', 0);
$imageInContent    = (bool) $params->get('image_in_content', 1);

$contentBg           = $params->get('content_bg', '#27ae60');
$contentBorderColor  = $params->get('content_border_color', 'transparent');
$contentBorderWidth  = (int) $params->get('content_border_width', 0);
$contentCenter       = (bool) $params->get('content_center', 0);

$showTitle      = (bool) $params->get('show_title', 1);
$titleTag       = $params->get('title_tag', 'h3');
$titleColor     = $params->get('title_color', '#ffffff');
$titleFontSize  = (int) $params->get('title_font_size', 20);

$showText       = (bool) $params->get('show_text', 1);
$textColor      = $params->get('text_color', '#ffffff');
$textFontSize   = (int) $params->get('text_font_size', 14);

$showButton         = (bool) $params->get('show_button', 1);
$buttonColor        = $params->get('button_color', '#ffffff');
$buttonBg           = $params->get('button_bg', '#27C07C');
$buttonFontSize      = (int) $params->get('button_font_size', 16);
$buttonColorHover    = $params->get('button_color_hover', '#ffffff');
$buttonBgHover       = $params->get('button_bg_hover', '#22a86a');

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$modulePath = 'modules/mod_dc_featuredboxes';
$wa->registerAndUseStyle('mod_dc_featuredboxes.style', $modulePath . '/media/css/style.css');

$cssVars = [
	'--dc-fbox-columns' => $columns,
	'--dc-fbox-lower' => $everySecondLower ? '1' : '0',
	'--dc-fbox-image-max-width' => $imageMaxWidth . '%',
	'--dc-fbox-image-max-pct' => $imageMaxWidth,
	'--dc-fbox-image-border-width' => $imageBorder ? $imageBorderWidth . 'px' : '0',
	'--dc-fbox-image-border-color' => $imageBorderColor,
	'--dc-fbox-content-bg' => $contentBg,
	'--dc-fbox-content-radius' => '1rem',
	'--dc-fbox-content-border-color' => $contentBorderColor,
	'--dc-fbox-content-border-width' => $contentBorderWidth . 'px',
	'--dc-fbox-title-color' => $titleColor,
	'--dc-fbox-title-font-size' => $titleFontSize . 'px',
	'--dc-fbox-text-color' => $textColor,
	'--dc-fbox-text-font-size' => $textFontSize . 'px',
	'--dc-fbox-btn-color' => $buttonColor,
	'--dc-fbox-btn-bg' => $buttonBg,
	'--dc-fbox-btn-font-size' => $buttonFontSize . 'px',
	'--dc-fbox-btn-color-hover' => $buttonColorHover,
	'--dc-fbox-btn-bg-hover' => $buttonBgHover,
];

$cssString = '#' . $modId . ' { ';
foreach ($cssVars as $key => $val) {
	$cssString .= $key . ': ' . $val . '; ';
}
$cssString .= ' }';
$wa->addInlineStyle($cssString, ['name' => 'mod_dc_featuredboxes_' . $module->id]);
?>

<div id="<?php echo $modId; ?>" class="dc-featuredboxes-wrapper dc-fbox-cols-<?php echo $columns; ?> <?php echo $everySecondLower ? 'dc-fbox-every-second-lower' : ''; ?> <?php echo $imageInContent ? 'dc-fbox-image-in-content' : 'dc-fbox-image-outside'; ?> <?php echo $contentCenter ? 'dc-fbox-content-center' : ''; ?> <?php echo htmlspecialchars($params->get('moduleclass_sfx', '')); ?>">
	<div class="dc-fbox-grid">
		<?php foreach ($boxes as $idx => $box) :
			$title      = trim($getBoxValue($box, 'title', ''));
			$text       = trim($getBoxValue($box, 'text', ''));
			$image      = trim($getBoxValue($box, 'image', ''));
			$buttonText = trim($getBoxValue($box, 'button_text', ''));
			$buttonLink = trim($getBoxValue($box, 'button_link', ''));

			$imgUrl = '';
			if ($image) {
				$clean = HTMLHelper::_('cleanImageURL', $image);
				$imgUrl = $clean ? (Uri::root(true) . '/' . ltrim($clean->url, '/')) : '';
			}
		?>
			<div class="dc-fbox-item">
				<?php if ($imageInContent) : ?>
					<div class="dc-fbox-content">
						<?php if ($showImage && $imgUrl) : ?>
							<div class="dc-fbox-image-wrap">
								<div class="dc-fbox-image-circle">
									<?php if ($imageLinked && $buttonLink) : ?>
										<a href="<?php echo htmlspecialchars($buttonLink); ?>" class="dc-fbox-image-link">
											<img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="dc-fbox-image" loading="lazy" />
										</a>
									<?php else : ?>
										<img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="dc-fbox-image" loading="lazy" />
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="dc-fbox-body">
							<?php if ($showTitle && $title !== '') : ?>
								<<?php echo $titleTag; ?> class="dc-fbox-title"><?php echo htmlspecialchars($title); ?></<?php echo $titleTag; ?>>
							<?php endif; ?>
							<?php if ($showText && $text !== '') : ?>
								<div class="dc-fbox-text"><?php echo $text; ?></div>
							<?php endif; ?>
							<?php if ($showButton && $buttonText !== '' && $buttonLink !== '') : ?>
								<a href="<?php echo htmlspecialchars($buttonLink); ?>" class="dc-fbox-btn"><?php echo htmlspecialchars($buttonText); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php else : ?>
					<?php if ($showImage && $imgUrl) : ?>
						<div class="dc-fbox-image-wrap dc-fbox-image-outside-wrap">
							<div class="dc-fbox-image-circle">
								<?php if ($imageLinked && $buttonLink) : ?>
									<a href="<?php echo htmlspecialchars($buttonLink); ?>" class="dc-fbox-image-link">
										<img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="dc-fbox-image" loading="lazy" />
									</a>
								<?php else : ?>
									<img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="dc-fbox-image" loading="lazy" />
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
					<div class="dc-fbox-content">
						<div class="dc-fbox-body">
							<?php if ($showTitle && $title !== '') : ?>
								<<?php echo $titleTag; ?> class="dc-fbox-title"><?php echo htmlspecialchars($title); ?></<?php echo $titleTag; ?>>
							<?php endif; ?>
							<?php if ($showText && $text !== '') : ?>
								<div class="dc-fbox-text"><?php echo $text; ?></div>
							<?php endif; ?>
							<?php if ($showButton && $buttonText !== '' && $buttonLink !== '') : ?>
								<a href="<?php echo htmlspecialchars($buttonLink); ?>" class="dc-fbox-btn"><?php echo htmlspecialchars($buttonText); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php if ($imageInContent && $showImage) : ?>
<script>
(function() {
	var wrapper = document.getElementById('<?php echo $modId; ?>');
	if (!wrapper || !wrapper.classList.contains('dc-fbox-image-in-content')) return;
	function setPadding() {
		wrapper.querySelectorAll('.dc-fbox-item').forEach(function(item) {
			var circle = item.querySelector('.dc-fbox-image-circle');
			if (circle) {
				var h = circle.offsetHeight;
				item.style.paddingTop = (h * 0.5 + 30) + 'px';
			}
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', setPadding);
	} else {
		setPadding();
	}
	window.addEventListener('load', setPadding);
	window.addEventListener('resize', setPadding);
})();
</script>
<?php endif; ?>
