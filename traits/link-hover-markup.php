<?php
/**
 * Link Hover Markup trait
 */
namespace Happy_Addons\Elementor\Traits;

defined('ABSPATH') || exit;

/**
 * Trait to load markup for link hover
 */

trait Link_Hover_Markup
{
    public static function render_metis_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--metis">About us</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_io_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--io">Shop the look</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_thebe_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--thebe">Track package</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_leda_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--leda" data-text="Our Philosophy">
                <span>Our Philosophy</span>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_ersa_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--ersa">
                <span>Downloads</span>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_elara_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--elara">
                <span>Studio</span>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_dia_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--dia">Portfolio</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_kale_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--kale">Explore more</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_carpo_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--carpo">Blog</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_helike_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--helike"><span>Discover</span></a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_mneme_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--mneme">Inquiries</a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_iocaste_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--iocaste">
                <span>Learn more</span>
                <svg class="link__graphic link__graphic--slide" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                    <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"></path>
                </svg>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_herse_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--herse">
                <span>Sign up</span>
                <svg class="link__graphic link__graphic--stroke link__graphic--arc" width="100%" height="18" viewBox="0 0 59 18"><path d="M.945.149C12.3 16.142 43.573 22.572 58.785 10.842" pathLength="1"/></svg>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_carme_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--carme">
                <span>Writings</span>
                <svg class="link__graphic link__graphic--stroke link__graphic--scribble" width="100%" height="9" viewBox="0 0 101 9"><path d="M.426 1.973C4.144 1.567 17.77-.514 21.443 1.48 24.296 3.026 24.844 4.627 27.5 7c3.075 2.748 6.642-4.141 10.066-4.688 7.517-1.2 13.237 5.425 17.59 2.745C58.5 3 60.464-1.786 66 2c1.996 1.365 3.174 3.737 5.286 4.41 5.423 1.727 25.34-7.981 29.14-1.294" pathLength="1"/></svg>
            </a>
        </link-hover>
EOF;
        echo $markup;
    }

    public static function render_eirene_markup( $settings = [], $post ){
        $markup = <<<EOF
        <link-hover class="content__item">
            <a href="#" class="link link--eirene"><span>Contact</span></a>
        </link-hover>
EOF;
        echo $markup;
    }
}