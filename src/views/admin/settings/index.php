<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Setting.php';

$admin_title = 'ตั้งค่าเว็บไซต์';
$admin_breadcrumb = 'แก้ไขภาพและข้อความที่แสดงในหน้าหลักของเว็บไซต์';

$settingModel = new Setting();
$message = '';
$error = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['settings'] ?? [];
    if (!empty($data)) {
        $settingModel->updateSettings($data);
        $message = 'บันทึกการตั้งค่าเรียบร้อยแล้ว';
    }
}

$settings = $settingModel->getAllSettingsFull();

// Group settings by section
$sections = [
    'hero' => ['label' => '🖼 Hero Section', 'keys' => ['hero_bg_image', 'hero_product_image', 'hero_title', 'hero_subtitle', 'hero_description']],
    'promo' => ['label' => '🎁 โปรโมชัน', 'keys' => ['promo1_title', 'promo1_subtitle', 'promo1_image', 'promo2_title', 'promo2_subtitle']],
    'story' => ['label' => '📖 เรื่องราวของไพราวี', 'keys' => ['story_image', 'story_title', 'story_description']],
    'articles' => ['label' => '📰 สาระน่ารู้', 'keys' => ['article1_image', 'article1_title', 'article1_description', 'article2_image', 'article2_title', 'article2_description']],
    'general' => ['label' => '⚙️ ทั่วไป', 'keys' => ['site_name', 'footer_copyright']],
];

// Index settings by key for easy lookup
$settingsMap = [];
foreach ($settings as $s) {
    $settingsMap[$s['setting_key']] = $s;
}

ob_start();
?>

<div class="p-6 md:p-10 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ตั้งค่าเว็บไซต์</h1>
            <p class="text-sm text-gray-500 mt-1">แก้ไขภาพและข้อความที่แสดงในหน้าหลักของเว็บไซต์</p>
        </div>
        <a href="<?= BASE_URL ?>/" target="_blank"
            class="flex items-center space-x-2 text-sm text-PRAIRAVEE-green border border-PRAIRAVEE-green px-4 py-2 rounded-lg hover:bg-PRAIRAVEE-light transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            <span>ดูหน้าหลัก</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="settings-form">
        <div class="space-y-8">

            <?php foreach ($sections as $sectionKey => $section): ?>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h2 class="text-base font-semibold text-gray-700"><?= $section['label'] ?></h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <?php foreach ($section['keys'] as $key): ?>
                            <?php if (!isset($settingsMap[$key]))
                                continue; ?>
                            <?php $setting = $settingsMap[$key]; ?>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <label
                                    class="text-sm font-medium text-gray-700 pt-2"><?= htmlspecialchars($setting['label']) ?></label>
                                <div class="md:col-span-2">
                                    <?php if ($setting['setting_type'] === 'image_url'): ?>
                                        <div x-data="{ url: '<?= htmlspecialchars($setting['setting_value'] ?? '') ?>' }">
                                            <input type="url" name="settings[<?= $key ?>]" x-model="url"
                                                value="<?= htmlspecialchars($setting['setting_value'] ?? '') ?>"
                                                placeholder="https://i.ibb.co/..."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-PRAIRAVEE-green focus:ring-1 focus:ring-PRAIRAVEE-green mb-2">
                                            <!-- Image Preview -->
                                            <div x-show="url" class="mt-2">
                                                <img :src="url" alt="Preview"
                                                    class="h-24 rounded-lg object-cover border border-gray-200 shadow-sm"
                                                    onerror="this.style.display='none'" x-on:load="$el.style.display='block'">
                                                <p class="text-xs text-gray-400 mt-1">Preview</p>
                                            </div>
                                        </div>
                                    <?php elseif ($setting['setting_type'] === 'textarea'): ?>
                                        <textarea name="settings[<?= $key ?>]" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-PRAIRAVEE-green focus:ring-1 focus:ring-PRAIRAVEE-green resize-none"><?= htmlspecialchars($setting['setting_value'] ?? '') ?></textarea>
                                    <?php else: ?>
                                        <input type="text" name="settings[<?= $key ?>]"
                                            value="<?= htmlspecialchars($setting['setting_value'] ?? '') ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-PRAIRAVEE-green focus:ring-1 focus:ring-PRAIRAVEE-green">
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- Save Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit"
                class="flex items-center space-x-2 bg-PRAIRAVEE-green text-white px-8 py-3 rounded-lg font-medium hover:bg-opacity-90 transition shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>บันทึกการตั้งค่าทั้งหมด</span>
            </button>
        </div>
    </form>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>