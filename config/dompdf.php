<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all of the dompdf options here.
    |
    */

    'show_warnings' => false,   // Throw an exception on warnings from dompdf

    'orientation' => 'portrait',

    'defines' => [

        'font_dir' => storage_path('fonts/'), // advised by dompdf (https://github.com/dompdf/dompdf/pull/782)

        /**
         * The location of the DOMPDF font cache directory
         *
         * This directory contains the cached font metrics for the fonts used by DOMPDF.
         * This directory can be the same as any other writable directory, as long as
         * the running process has access to write to it. It does not need to be
         * writable by the webserver, as this
         */
        'font_cache' => storage_path('fonts/'),

        /**
         * The location of a temporary directory.
         *
         * The directory specified must be writeable by the webserver process.
         * The temporary directory is required to download remote images and when
         * using the PFDLib back end.
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * ==== IMPORTANT ====
         *
         * dompdf's "chroot": Prevents dompdf from accessing system files or other
         * files on the webserver. All local files opened by dompdf must be in a
         * subdirectory of this directory. DO NOT set it to '/' since this could
         * allow an attacker to use dompdf to read any files on the server. This
         * should be an absolute path.
         * This value can be overridden by dompdf options.
         *
         * ==== IMPORTANT ====
         */
        'chroot' => base_path(),

        /**
         * Most likely you don't want to bother changing these which is why
         * we set them to the same as the global defaults found in
         * lib/dompdf/src/Options.php.
         *
         * NOTE: These values are overridden by the values set in
         *       lib/dompdf/src/Options.php. If you absolutely need to
         *       override these, change them there.
         */

        /**
         * The default paper size.
         *
         * North America standard is "letter"; other countries generally "a4"
         *
         * @see \Dompdf\Options::setDefaultPaperSize()
         */
        'default_paper_size' => 'a4',

        /**
         * The default paper orientation.
         *
         * The orientation of the page (portrait or landscape).
         *
         * @see \Dompdf\Options::setDefaultPaperOrientation()
         */
        'default_paper_orientation' => 'portrait',

        /**
         * The default font family
         *
         * Used if no suitable fonts can be found. This must exist in the font folder.
         * @see \Dompdf\Options::setDefaultFont()
         */
        'default_font' => 'serif',

        /**
         * Image DPI setting
         *
         * This setting determines the default DPI setting for images and fonts. The
         * DPI may be overridden by a CSS style rule.
         * 96 dpi is the default setting for web browsers
         * @see \Dompdf\Options::setDpi()
         */
        'dpi' => 96,

        /**
         * A ratio applied to the fonts height to be more like browsers' line height
         * @see \Dompdf\Options::setFontHeightRatio()
         */
        'font_height_ratio' => 1.1,

        /**
         * Enable embedded PHP
         *
         * If this setting is set to true then DOMPDF will automatically evaluate
         * embedded PHP contained within <script type="text/php"> ... </script> tags.
         *
         * ==== IMPORTANT ====
         * Enabling embedded PHP is a major security risk. Embedded scripts are run
         * with the same level of system access available to dompdf. Set this
         * option to false (recommended) unless you are sure you know what you are
         * doing.
         * @see \Dompdf\Options::setIsPhpEnabled()
         */
        'is_php_enabled' => false,

        /**
         * Enable remote file access
         *
         * If this setting is set to true, DOMPDF will access remote sites for
         * images and CSS files as required.
         * @see \Dompdf\Options::setIsRemoteEnabled()
         */
        'is_remote_enabled' => true,

        /**
         * Enable inline JavaScript
         *
         * If this setting is set to true then DOMPDF will automatically insert
         * JavaScript code contained within <script type="text/javascript"> ... </script> tags.
         *
         * ==== IMPORTANT ====
         * Enabling inline JavaScript is a major security risk. Embedded scripts are run
         * with the same level of system access available to dompdf. Set this
         * option to false (recommended) unless you are sure you know what you are
         * doing.
         * @see \Dompdf\Options::setIsJavascriptEnabled()
         */
        'is_javascript_enabled' => false,

        /**
         * Enable inline CSS
         *
         * If this setting is set to true then DOMPDF will automatically insert
         * CSS code contained within <style> ... </style> tags.
         * @see \Dompdf\Options::setIsCssFloatEnabled()
         */
        'is_css_float_enabled' => false,

        /**
         * Use the more-than-experimental HTML5 Lib parser
         * @see \Dompdf\Options::setIsHtml5ParserEnabled()
         */
        'is_html5_parser_enabled' => true,

        /**
         * Enable fonts subsetting
         *
         * When this is set to true, dompdf will embed a subset of the TrueType
         * fonts used in the document. This subsetting can reduce the size of
         * the resulting PDF, but will prevent the PDF from displaying correctly
         * if the PDF is edited in a tool that does not support subsetted fonts.
         * @see \Dompdf\Options::setIsFontSubsettingEnabled()
         */
        'is_font_subsetting_enabled' => false,

        /**
         * Debug PREG
         *
         * @see \Dompdf\Options::setDebugPreg()
         */
        'debug_preg' => false,

        /**
         * Debug Keep Temp
         *
         * @see \Dompdf\Options::setDebugKeepTemp()
         */
        'debug_keep_temp' => false,

        /**
         * Debug Css
         *
         * @see \Dompdf\Options::setDebugCss()
         */
        'debug_css' => false,

        /**
         * Debug Layout
         *
         * @see \Dompdf\Options::setDebugLayout()
         */
        'debug_layout' => false,

        /**
         * Debug Layout Lines
         *
         * @see \Dompdf\Options::setDebugLayoutLines()
         */
        'debug_layout_lines' => false,

        /**
         * Debug Layout Blocks
         *
         * @see \Dompdf\Options::setDebugLayoutBlocks()
         */
        'debug_layout_blocks' => false,

        /**
         * Debug Layout Inline
         *
         * @see \Dompdf\Options::setDebugLayoutInline()
         */
        'debug_layout_inline' => false,

        /**
         * Debug Layout Padding Box
         *
         * @see \Dompdf\Options::setDebugLayoutPaddingBox()
         */
        'debug_layout_padding_box' => false,
    ],
];
