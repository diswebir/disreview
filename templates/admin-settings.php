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

		<div class="dr-tabs">
			<button type="button" class="dr-tab is-active" data-tab="settings"><?php echo esc_html__( 'تنظیمات', 'disreview' ); ?></button>
			<button type="button" class="dr-tab" data-tab="help"><?php echo esc_html__( 'راهنما', 'disreview' ); ?></button>
		</div>

		<div class="dr-tab-panel is-active" id="dr-tab-settings">
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
					<label class="dr-check dr-check-full"><input type="checkbox" name="disreview_settings[auto_detect]" value="yes" <?php checked( $settings['auto_detect'], 'yes' ); ?>> <?php echo esc_html__( 'اعمال خودکار روی ووکامرس/EDD وقتی فعال باشند', 'disreview' ); ?></label>
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
							<label for="dr-star"><?php echo esc_html__( 'رنگ ستاره‌ها', 'disreview' ); ?></label>
							<input type="text" id="dr-star" name="disreview_settings[star_color]" class="dr-color" value="<?php echo esc_attr( $settings['star_color'] ); ?>">
						</div>

						<div class="dr-field">
							<label for="dr-muted"><?php echo esc_html__( 'رنگ متن ملایم (اختیاری)', 'disreview' ); ?></label>
							<input type="text" id="dr-muted" name="disreview_settings[muted_color]" class="dr-color" value="<?php echo esc_attr( $settings['muted_color'] ); ?>">
						</div>

					<div class="dr-field">
						<label for="dr-radius"><?php echo esc_html__( 'گردی گوشه‌ها (px)', 'disreview' ); ?></label>
						<input type="number" id="dr-radius" name="disreview_settings[border_radius]" min="0" max="40" value="<?php echo esc_attr( $settings['border_radius'] ); ?>">
					</div>

					<div class="dr-field dr-field-full">
						<label for="dr-font"><?php echo esc_html__( 'فونت سفارشی (اختیاری)', 'disreview' ); ?></label>
						<input type="text" id="dr-font" name="disreview_settings[font_family]" placeholder="مثلاً: Vazirmatn, Tahoma, sans-serif" value="<?php echo esc_attr( $settings['font_family'] ); ?>">
					</div>

					<div class="dr-field dr-field-full">
						<label for="dr-custom"><?php echo esc_html__( 'کد CSS سفارشی (اولویت بالا)', 'disreview' ); ?></label>
						<textarea id="dr-custom" name="disreview_settings[custom_css]" rows="4" placeholder=".disreview-active .comment { ... }"><?php echo esc_textarea( $settings['custom_css'] ); ?></textarea>
					</div>
				</div>

				<div class="dr-extra-toggles">
					<label class="dr-check"><input type="checkbox" name="disreview_settings[enable_avg]" value="yes" <?php checked( $settings['enable_avg'], 'yes' ); ?>> <?php echo esc_html__( 'نمایش امتیاز میانگین', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[enable_dark]" value="yes" <?php checked( $settings['enable_dark'], 'yes' ); ?>> <?php echo esc_html__( 'حالت تاریک خودکار (بر اساس سیستم)', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[load_mobile]" value="yes" <?php checked( $settings['load_mobile'], 'yes' ); ?>> <?php echo esc_html__( 'اعمال استایل در موبایل', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[show_count]" value="yes" <?php checked( $settings['show_count'], 'yes' ); ?>> <?php echo esc_html__( 'نمایش شمارنده نظرات در کنار عنوان', 'disreview' ); ?></label>
					<label class="dr-check"><input type="checkbox" name="disreview_settings[child_override]" value="yes" <?php checked( $settings['child_override'], 'no' ); ?>> <?php echo esc_html__( 'اجازه بازنویسی تم توسط قالب کودک', 'disreview' ); ?></label>
					<p class="dr-note"><?php echo esc_html__( 'برای بازنویسی، فایل را در مسیر قالب‌کودک/DisReview/theme-نام‌تم.css قرار دهید.', 'disreview' ); ?></p>
				</div>

				<h2><?php echo esc_html__( '۴. بکاپ تنظیمات (Import / Export)', 'disreview' ); ?></h2>
				<div class="dr-io">
					<a class="dr-io-btn" href="<?php echo esc_url( wp_nonce_url( admin_url( 'options-general.php?page=disreview&disreview_export=1' ), 'disreview_export_nonce' ) ); ?>"><?php echo esc_html__( 'دانلود تنظیمات (JSON)', 'disreview' ); ?></a>
					<form method="post" enctype="multipart/form-data" class="dr-io-form">
						<?php wp_nonce_field( 'disreview_import_nonce' ); ?>
						<input type="file" name="disreview_import_file" accept=".json" required>
						<button type="submit" name="disreview_import" class="dr-io-btn dr-io-btn-alt"><?php echo esc_html__( 'بارگذاری تنظیمات', 'disreview' ); ?></button>
					</form>
				</div>

				<?php submit_button( __( 'ذخیره تنظیمات', 'disreview' ), 'dr-btn' ); ?>
			</div><!-- /.dr-main -->
			</div><!-- /.dr-tab-panel settings -->

			<div class="dr-tab-panel" id="dr-tab-help">
				<div class="dr-card">
					<h2><?php echo esc_html__( 'راهنمای DisReview', 'disreview' ); ?></h2>
					<ol class="dr-help-list">
						<li><?php echo esc_html__( 'از تب تنظیمات، یکی از ۸ تم را انتخاب کنید.', 'disreview' ); ?></li>
						<li><?php echo esc_html__( 'پلتفرم‌های مقصد (وردپرس/ووکامرس/EDD) را فعال کنید؛ گزینه «اعمال خودکار» ووکامرس و EDD را وقتی نصب باشند خودکار پوشش می‌دهد.', 'disreview' ); ?></li>
						<li><?php echo esc_html__( 'رنگ اصلی، فونت و گردی گوشه‌ها را سفارشی کنید.', 'disreview' ); ?></li>
						<li><?php echo esc_html__( 'از بخش بکاپ می‌توانید تنظیمات را خروجی/ورودی (JSON) بگیرید.', 'disreview' ); ?></li>
						<li><?php echo esc_html__( 'شورت‌کد [disreview_top] نظرات برتر را هرجا نمایش می‌دهد.', 'disreview' ); ?></li>
						<li><?php echo esc_html__( 'با فعال‌سازی «بازنویسی توسط قالب کودک» فایل تم را در مسیر child-theme/disreview/theme-NAME.css قرار دهید.', 'disreview' ); ?></li>
					</ol>
					<h2><?php echo esc_html__( 'پشتیبانی', 'disreview' ); ?></h2>
					<p class="dr-note"><?php echo esc_html__( 'این افزونه رایگان و متن‌باز است. برای گزارش مشکل از بخش پشتیبانی مخزن استفاده کنید.', 'disreview' ); ?></p>
				</div>
			</div><!-- /.dr-tab-panel help -->

			<!-- Live preview column -->
			<div class="dr-card dr-preview">
				<h2><?php echo esc_html__( 'پیش‌نمایش زنده', 'disreview' ); ?></h2>
				<div class="dr-preview-tools">
					<button type="button" class="dr-view-btn is-active" data-view="desktop" title="دسکتاپ">🖥️</button>
					<button type="button" class="dr-view-btn" data-view="mobile" title="موبایل">📱</button>
				</div>
				<div class="dr-preview-frame disreview-active dr-theme-<?php echo esc_attr( ! empty( $settings['theme'] ) ? $settings['theme'] : 'cards' ); ?>" id="drPreview">
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
			</div><!-- /.dr-grid -->
	</form>
</div>
