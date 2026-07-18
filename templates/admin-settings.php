<?php
/**
 * Admin settings template (rendered by DisReview_Admin).
 *
 * @package DisReview
 *
 * @var array $settings Current settings.
 * @var array $themes   Registered themes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="disreview-wrap">
	<div class="disreview-header">
		<div class="dr-logo"><?php echo esc_html__( 'DisReview', 'disreview' ); ?></div>
		<h1><?php echo esc_html__( 'تنظیمات استایل نظرات و کامنت‌ها', 'disreview' ); ?></h1>
		<p class="dr-sub"><?php echo esc_html__( 'ظاهر بخش نظرات وردپرس، ووکامرس و EDD را با یک کلیک زیباتر کنید.', 'disreview' ); ?></p>
	</div>

	<form method="post" action="options.php" class="disreview-form">
		<?php settings_fields( 'disreview_settings_group' ); ?>

		<div class="dr-grid">
			<!-- Main column -->
			<div class="dr-card dr-main">
				<h2><?php echo esc_html__( '۱. انتخاب تم نظرات', 'disreview' ); ?></h2>
				<p class="dr-hint"><?php echo esc_html__( 'یکی از ۶ تم آماده را انتخاب کنید. تغییرات را در پیش‌نمایش سمت راست ببینید.', 'disreview' ); ?></p>

				<div class="dr-theme-grid">
					<?php foreach ( $themes as $key => $t ) : ?>
						<label class="dr-theme-pick" data-theme="<?php echo esc_attr( $key ); ?>">
							<input type="radio" name="disreview_settings[theme]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $settings['theme'], $key ); ?>>
							<span class="dr-swatch dr-swatch-<?php echo esc_attr( $key ); ?>"></span>
							<span class="dr-theme-label"><?php echo esc_html( $t['label'] ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>

				<h2><?php echo esc_html__( '۲. فعال‌سازی روی پلتفرم‌ها', 'disreview' ); ?></h2>
				<div class="dr-toggles">
					<label class="dr-switch">
						<input type="checkbox" name="disreview_settings[enable_wp]" value="yes" <?php checked( $settings['enable_wp'], 'yes' ); ?>>
						<span class="dr-track"></span>
						<span class="dr-switch-label"><?php echo esc_html__( 'نظرات هسته وردپرس', 'disreview' ); ?></span>
					</label>

					<label class="dr-switch">
						<input type="checkbox" name="disreview_settings[enable_wc]" value="yes" <?php checked( $settings['enable_wc'], 'yes' ); ?> <?php disabled( ! DisReview::is_woocommerce_active() ); ?>>
						<span class="dr-track"></span>
						<span class="dr-switch-label">
							<?php echo esc_html__( 'ووکامرس (نظرات محصولات)', 'disreview' ); ?>
							<?php if ( ! DisReview::is_woocommerce_active() ) : ?>
								<small class="dr-warn"><?php echo esc_html__( 'افزونه ووکامرس فعال نیست', 'disreview' ); ?></small>
							<?php endif; ?>
						</span>
					</label>

					<label class="dr-switch">
						<input type="checkbox" name="disreview_settings[enable_edd]" value="yes" <?php checked( $settings['enable_edd'], 'yes' ); ?> <?php disabled( ! DisReview::is_edd_active() ); ?>>
						<span class="dr-track"></span>
						<span class="dr-switch-label">
							<?php echo esc_html__( 'Easy Digital Downloads', 'disreview' ); ?>
							<?php if ( ! DisReview::is_edd_active() ) : ?>
								<small class="dr-warn"><?php echo esc_html__( 'افزونه EDD فعال نیست', 'disreview' ); ?></small>
							<?php endif; ?>
						</span>
					</label>
				</div>

				<h2><?php echo esc_html__( '۳. شخصی‌سازی', 'disreview' ); ?></h2>
				<div class="dr-fields">
					<div class="dr-field">
						<label for="dr-primary"><?php echo esc_html__( 'رنگ اصلی (برند)', 'disreview' ); ?></label>
						<input type="text" id="dr-primary" name="disreview_settings[primary_color]" class="dr-color" value="<?php echo esc_attr( $settings['primary_color'] ); ?>">
					</div>

					<div class="dr-field">
						<label for="dr-radius"><?php echo esc_html__( 'گردی گوشه‌ها (px)', 'disreview' ); ?></label>
						<input type="number" id="dr-radius" name="disreview_settings[border_radius]" min="0" max="40" value="<?php echo esc_attr( $settings['border_radius'] ); ?>">
					</div>

					<div class="dr-field dr-field-full">
						<label for="dr-font"><?php echo esc_html__( 'فونت سفارشی (اختیاری)', 'disreview' ); ?></label>
						<input type="text" id="dr-font" name="disreview_settings[font_family]" placeholder="مثلاً: Vazirmatn, Tahoma, sans-serif" value="<?php echo esc_attr( $settings['font_family'] ); ?>">
					</div>
				</div>

				<div class="dr-extra-toggles">
					<label class="dr-check"><input type="checkbox" name="disreview_settings[enable_avg]" value="yes" <?php checked( $settings['enable_avg'], 'yes' ); ?>> <?php echo esc_html__( 'نمایش امتیاز میانگین', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[enable_dark]" value="yes" <?php checked( $settings['enable_dark'], 'yes' ); ?>> <?php echo esc_html__( 'حالت تاریک خودکار (بر اساس سیستم)', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[load_mobile]" value="yes" <?php checked( $settings['load_mobile'], 'yes' ); ?>> <?php echo esc_html__( 'اعمال استایل در موبایل', 'disreview' ); ?></label>
				</div>

				<?php submit_button( __( 'ذخیره تنظیمات', 'disreview' ), 'dr-btn' ); ?>
			</div>

			<!-- Live preview column -->
			<div class="dr-card dr-preview">
				<h2><?php echo esc_html__( 'پیش‌نمایش زنده', 'disreview' ); ?></h2>
				<div class="dr-preview-frame disreview-active dr-theme-<?php echo esc_attr( $settings['theme'] ); ?>" id="drPreview">
					<div class="dr-preview-head">
						<h3><?php echo esc_html__( '۳ دیدگاه', 'disreview' ); ?></h3>
						<span class="dr-avg">★ 4.6</span>
					</div>

					<div class="dr-comment">
						<div class="dr-avatar">س</div>
						<div class="dr-body">
							<div class="dr-meta"><strong>سارا</strong> <span class="dr-stars">★★★★★</span></div>
							<p>عالی بود! کیفیت ساخت واقعا خوبه و ارسال هم سریع انجام شد.</p>
							<div class="dr-actions"><a href="#">پاسخ</a></div>
						</div>
					</div>

					<div class="dr-comment">
						<div class="dr-avatar">م</div>
						<div class="dr-body">
							<div class="dr-meta"><strong>محمد</strong> <span class="dr-stars">★★★★☆</span></div>
							<p>قیمتش نسبت به بقیه منصفانه‌تره، پیشنهاد می‌کنم.</p>
							<div class="dr-actions"><a href="#">پاسخ</a></div>
						</div>
					</div>

					<div class="dr-form-preview">
						<strong><?php echo esc_html__( 'نظر خود را بنویسید', 'disreview' ); ?></strong>
						<textarea rows="2" readonly placeholder="<?php echo esc_attr__( 'متن نظر...', 'disreview' ); ?>"></textarea>
						<button type="button" class="dr-demo-btn"><?php echo esc_html__( 'ارسال نظر', 'disreview' ); ?></button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
