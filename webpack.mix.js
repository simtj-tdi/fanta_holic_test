const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
mix.js('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js')

mix.styles(['resources/sass/style.scss'],'public/css/style.css');

mix.scripts(['resources/js/plugin/google_map.js'],'public/js/google_map.js');
mix.scripts([
    'resources/js/boardcontrol/boardcontrol.js',
    'resources/js/boardcontrol/tag_list_search.js',
    // 'resources/js/boardcontrol/search_list_search.js',
    'resources/js/boardcontrol/menu/controlmenu.js',
    'resources/js/boardcontrol/menu/sidemenu.js',
    'resources/js/boardcontrol/menu/dropmenu.js',
    'resources/js/boardcontrol/modal/open.js',
    'resources/js/boardcontrol/modal/modify.js',
    'resources/js/boardcontrol/modal/individual_modify.js',
    'resources/js/boardcontrol/modal/create.js'
],'public/js/board_control.js');
mix.scripts([
    // 'node_modules/jquery/dist/jquery.js',
    'node_modules/popper.js/dist/umd/popper.js',
    // 'node_modules/bootstrap/dist/js/bootstrap.js',
    // 'node_modules/pace-progress/pace.js',
    'node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',
    'node_modules/@coreui/coreui/dist/js/coreui.js',

    // bootstrap-datepicker
    'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
    'node_modules/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ko.min.js',

    // bootstrap-select
    // 'node_modules/bootstrap-select/dist/js/bootstrap-select.js',

    'node_modules/moment/moment.js',
    'node_modules/daterangepicker/daterangepicker.js',


    // select2
    'node_modules/select2/dist/js/select2.js',
    'node_modules/select2/dist/js/i18n/ko.js',

    // pnotify
    // 'node_modules/pnotify/dist/iife/PNotify.js',
    // 'node_modules/pnotify/dist/iife/PNotifyButtons.js',

    // jquery-easy-loading
    // 'node_modules/jquery-easy-loading/dist/jquery.loading.js',

    // // tagInput
    // 'resources/js/pinxy19/jquery.dragoptions.js',

    // jquery-loadingModal
    'node_modules/jquery-loadingModal/js/jquery.loadingModal.js',


    // 'node_modules/jquery/jquery.js',

    // jQuery UI
    'node_modules/jquery-ui/ui/widget.js',
    'node_modules/jquery-ui/ui/widgets/mouse.js',
    'node_modules/jquery-ui/ui/disable-selection.js',
    'node_modules/jquery-ui/ui/widgets/sortable.js',
    'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.js',
    'node_modules/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',

    //sweetalert2
    'node_modules/sweetalert2/dist/sweetalert2.all.js'
], 'public/js/admin.js');

mix.styles([
    'node_modules/@coreui/icons/css/coreui-icons.css',
    'node_modules/flag-icon-css/css/flag-icon.css',
    'node_modules/font-awesome/css/font-awesome.css',
    'node_modules/simple-line-icons/css/simple-line-icons.css',

    // 'node_modules/@coreui/coreui/dist/css/style.css',
    'node_modules/@coreui/coreui/dist/css/coreui.css',

    // pace-progress css coreui 수정본 사용
    // 'resources/assets/coreui/css/pace.css',

    // bootstrap-datepicker
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',

    // // pnotify
    // 'node_modules/pnotify/dist/PNotifyBrightTheme.css',

    // bootstrap-select
    // 'node_modules/bootstrap-select/dist/css/bootstrap-select.css',

    'node_modules/daterangepicker/daterangepicker.css',


    // select2
    'node_modules/select2/dist/css/select2.css',

    // select2 bootstrap4 theme
    'node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css',

    // jquery-loadingModal
    'node_modules/jquery-loadingModal/css/jquery.loadingModal.css',

    'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css',

    // jQuery UI
    'node_modules/jquery-ui/themes/base/all.css',

    //sweetalert2
    'node_modules/dist/sweetalert2.css'

], 'public/css/admin.css');

mix.copyDirectory('node_modules/@coreui/icons/fonts', 'public/fonts');
mix.copyDirectory('node_modules/font-awesome/fonts', 'public/fonts');
mix.copyDirectory('node_modules/simple-line-icons/fonts', 'public/fonts');
mix.copyDirectory('node_modules/flag-icon-css/flags', 'public/flags');

// mix.copyDirectory('resources/assets/img', 'public/img');
mix.copyDirectory('resources/assets/images', 'public/images');
