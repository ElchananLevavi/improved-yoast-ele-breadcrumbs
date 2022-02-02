<?php
namespace ImprovedBreadcrumbs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use ElementorPro\Modules\ThemeElements\Widgets\Breadcrumbs;
use WPSEO_Breadcrumbs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Improved_Breadcrumbs extends Breadcrumbs {
    public function get_name() {
        return 'improved-breadcrumbs';
    }

    public function get_title() {
        return __( 'Improved Breadcrumbs', 'improved-breadcrumbs' );
    }

	protected function _register_controls() {
		parent::_register_controls();

		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'html_tag',
			]
		);

		$this->add_control(
			'remove_current_page',
			[
				'label' => 'Remove current page',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'improved-breadcrumbs' ),
				'label_off' => esc_html__( 'No', 'improved-breadcrumbs' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'remove-current-page-',
			]
		);

        $this->add_control(
			'flip_separator',
			[
				'label' => 'Flip separator',
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'improved-breadcrumbs' ),
				'label_off' => esc_html__( 'No', 'improved-breadcrumbs' ),
				'return_value' => 'yes',
				'default' => 'no',
				'prefix_class' => 'flip-separator-',
			]
		);

        $this->add_control(
			'show_only_one_level',
			[
				'label' => 'Show only one step backwards',
                'description' => 'Shorten & minimal breadcrumbs',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'no' => 'No',
                    'on_mobile' => 'On mobile',
					'always' => 'Always',
				],
                'default' => 'no',
                'label_block' => 'true',
				'prefix_class' => 'shorten-breadcrumbs-',
				
			]
		);
		$this->end_injection();
	}

    private function get_html_tag() {
		$html_tag = $this->get_settings( 'html_tag' );

		if ( empty( $html_tag ) ) {
			$html_tag = 'nav';
		}

		return Utils::validate_html_tag( $html_tag );
	}

    // Remove the current page from the Yoast breadcrumb trail, rendering the parent crumb as a link.
    public function breadcrumbs_remove_last($link_output) {
        if ( strpos( $link_output, 'breadcrumb_last' ) !== false ) {
            $link_output = '';
        }
        return $link_output;
    }

    protected function render() {
     
        if ($this->get_settings( 'remove_current_page') === 'yes' ) { 
            add_action( 'wpseo_breadcrumb_single_link', [ $this, 'breadcrumbs_remove_last' ] );
        }

        if ( class_exists( '\WPSEO_Breadcrumbs' ) ) {
            $html_tag = $this->get_html_tag();
            WPSEO_Breadcrumbs::breadcrumb( '<' . $html_tag . ' id="breadcrumbs" aria-label="Breadcrumb"><ul><li>', '</li></ul></' . $html_tag . '>' );
        }
        echo '<style>
        #breadcrumbs ul {padding: 0; list-style: none;} li {display: inline;} 
        .remove-current-page-yes.shorten-breadcrumbs-always li:not(:last-child){ display: none; }
        .shorten-breadcrumbs-always:not(.remove-current-page-yes) li:nth-last-child(n+3){ display: none; }
        .flip-separator-yes .separator { display: inline-block; -webkit-transform: scaleX(-1);  transform: scaleX(-1); }
        @media(max-width:767px){
            .remove-current-page-yes.shorten-breadcrumbs-on_mobile li:not(:last-child){ display: none; }
            .shorten-breadcrumbs-on_mobile:not(.remove-current-page-yes) li:nth-last-child(n+3){ display: none; }
        }
        </style>';
    }
}

