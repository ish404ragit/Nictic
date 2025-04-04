<?php
namespace Elementor;

use \Elementor\ElementsKit_Widget_Caldera_Forms_Handler as Handler;
use \ElementsKit_Lite\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if (! defined( 'ABSPATH' ) ) exit;

class ElementsKit_Widget_Caldera_Forms extends Widget_Base {
	use \ElementsKit_Lite\Widgets\Widget_Notice;

    public $base;

    public function get_name() {
        return Handler::get_name();
    }

    public function get_title() {
        return Handler::get_title();
    }

    public function get_icon() {
        return Handler::get_icon();
    }

    public function get_categories() {
        return Handler::get_categories();
    }
    
    public function get_keywords() {
        return Handler::get_keywords();
    }

    public function get_help_url() {
        return 'https://wpmet.com/doc/caldera-form/';
	}

    protected function is_dynamic_content(): bool {
        return false;
    }

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}
	
	function elementskit_caldera_forms_options() {
		if ( class_exists( 'Caldera_Forms' ) ) {
			$caldera_forms = \Caldera_Forms_Forms::get_forms( true, true );
			$form_options  = ['0' => esc_html__( 'Select Form', 'elementskit-lite' )];
			$form          = array();
			if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
				foreach ( $caldera_forms as $form ) {
					if ( isset($form['ID']) and isset($form['name'])) {
						$form_options[$form['ID']] = $form['name'];
					}
				}
			}
		} else {
			$form_options = ['0' => esc_html__( 'Form Not Found!', 'elementskit-lite' ) ];
		}
		return $form_options;
	}

    protected function register_controls() {
        $this->start_controls_section(
            'ekit_caldera_form_section_tab', [
                'label' =>esc_html__( 'Caldera Forms', 'elementskit-lite' ),
            ]
        );

		$this->add_control(
			'ekit_caldera_form_list',
			[
				'label'   => esc_html__( 'Select Form', 'elementskit-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->elementskit_caldera_forms_options(),
			]
		);

        $this->end_controls_section();

        // label
		$this->start_controls_section(
			'ekit_contact_form_input_label_style',
			[
				'label' => esc_html__( 'Label', 'elementskit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_contact_form_input_label_typography',
				'label' => esc_html__( 'Typography', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form label',
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_label_color',
			[
				'label' => esc_html__( 'Color', 'elementskit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .ekit-form form label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_label_margin',
			[
				'label' => esc_html__( 'Margin', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
			'ekit_contact_form_input_label_hint_heading',
			[
				'label' => esc_html__( 'Hint', 'elementskit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_contact_form_input_label_hint_typography',
				'label' => esc_html__( 'Typography', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form label span',
			]
        );

        $this->add_responsive_control(
			'ekit_contact_form_input_label_hint_color',
			[
				'label' => esc_html__( 'Color', 'elementskit-lite' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .ekit-form form label span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// input style
		$this->start_controls_section(
			'ekit_contact_form_input_style',
			[
				'label' => esc_html__( 'Input', 'elementskit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_padding',
			[
				'label' => esc_html__( 'Padding', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_width',
			[
				'label' => esc_html__( 'Width', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"])' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_height',
			[
				'label' => esc_html__( 'Height', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"])' => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .ekit-form form textarea' => 'height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_margin_bottom',
			[
				'label' => esc_html__( 'Margin Bottom', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form .form-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
			'ekit_contact_form_input_style_textarea_heading',
			[
				'label' => esc_html__( 'Textarea', 'elementskit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'ekit_contact_form_input_style_textarea_height',
			[
				'label' => esc_html__( 'Height', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 176,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 176,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_padding_textarea',
			[
				'label' => esc_html__( 'Padding', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_contact_form_input_style_padding_textarea_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

        $this->start_controls_tabs(
            'ekit_contact_form_input_normal_and_hover_tabs'
        );
        $this->start_controls_tab(
            'ekit_contact_form_input_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'elementskit-lite' ),
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_contact_form_input_style_background',
				'label' => esc_html__( 'Background', 'elementskit-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ekit-form form.caldera_forms_form .form-group input.form-control:not([type="submit"]), {{WRAPPER}} .ekit-form form textarea',
				'exclude' => ['image'] // PHPCS:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input:not([type="submit"])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_contact_form_input_style_border',
				'label' => esc_html__( 'Border', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"]), {{WRAPPER}} .ekit-form form textarea',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_contact_form_input_style_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit-lite' ),
                'selector' => '
                            {{WRAPPER}} .ekit-form form input:not([type="submit"]),
                            {{WRAPPER}} .ekit-form form textarea'
                            ,
			]
		);

		$this->end_controls_tab();
        $this->start_controls_tab(
            'ekit_contact_form_input_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'elementskit-lite' ),
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_contact_form_input_hover_style_background',
				'label' => esc_html__( 'Background', 'elementskit-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ekit-form form.caldera_forms_form .form-group input.form-control:not([type="submit"]):hover, {{WRAPPER}} .ekit-form form textarea:hover',
				'exclude' => ['image'] // PHPCS:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_hover_style_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input:not([type="submit"]):hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_contact_form_input_hover_style_border',
				'label' => esc_html__( 'Border', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"]):hover, {{WRAPPER}} .ekit-form form textarea:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_contact_form_input_hover_style_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit-lite' ),
                'selector' => '
                            {{WRAPPER}} .ekit-form form input.form-control:not([type="submit"]):hover,
                            {{WRAPPER}} .ekit-form form textarea:hover'
                            ,
			]
		);

		$this->end_controls_tab();
        $this->start_controls_tab(
            'ekit_contact_form_input_focus_tab',
            [
                'label' => esc_html__( 'Focus', 'elementskit-lite' ),
            ]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_contact_form_input_focus_style_background',
				'label' => esc_html__( 'Background', 'elementskit-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ekit-form form.caldera_forms_form .form-group input.form-control:not([type="submit"]):focus, {{WRAPPER}} .ekit-form form textarea:focus',
				'exclude' => ['image'] // PHPCS:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_focus_style_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input:not([type="submit"]):focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_contact_form_input_focus_style_border',
				'label' => esc_html__( 'Border', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input.form-control:not([type="submit"]):focus, {{WRAPPER}} .ekit-form form textarea.form-control:focus',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_contact_form_input_focus_style_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit-lite' ),
                'selector' => '
                            {{WRAPPER}} .ekit-form form input:not([type="submit"]):focus,
                            {{WRAPPER}} .ekit-form form textarea.form-control:focus'
                            ,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_control(
            'ekit_contact_form_input_style_typography_heading',
            [
                'label' => esc_html__( 'Typography', 'elementskit-lite' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_contact_form_input_typography',
                'label' => esc_html__( 'Typography', 'elementskit-lite' ),
                'selector' => '{{WRAPPER}} .ekit-form form input:not([type="submit"]), .wpcf7-form input:not([type="submit"]), .wpcf7-form textarea, .ekit-wid-con .ekit-form form textarea',
            ]
        );

        $this->add_responsive_control(
            'ekit_contact_form_input_style_font_color',
            [
                'label' => esc_html__( 'Color', 'elementskit-lite' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"])' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-form textarea' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-wid-con .ekit-form form textarea' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'ekit_contact_form_input_style_placeholder_heading',
			[
				'label' => esc_html__( 'Placeholder', 'elementskit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_input_style_placeholder_font_size',
			[
				'label' => esc_html__( 'Font Size', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input:not([type="submit"])::-webkit-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form input:not([type="submit"])::-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form input:not([type="submit"]):-ms-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"]):-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}} .ekit-form form textarea::-webkit-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea::-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea:-ms-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-form form textarea:-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
            'ekit_contact_form_input_placeholder_font_color',
            [
                'label' => esc_html__( 'Placeholder Color', 'elementskit-lite' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"]):-ms-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form input:not([type="submit"]):-moz-placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .ekit-form form textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form textarea::-moz-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form textarea:-ms-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ekit-form form textarea:-moz-placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();



		$this->start_controls_section(
			'ekit_contact_form_button_style_holder',
			[
				'label' => esc_html__( 'Button', 'elementskit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_alignment',
			[
				'label'    => esc_html__( 'Alignment', 'elementskit-lite' ),
				'type'     => Controls_Manager::CHOOSE,
				'options'  => [
					'left'   => [
						'title' => esc_html__( 'Left', 'elementskit-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'elementskit-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'  => 'left',
				'selectors'=> [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_contact_form_button_typography',
				'label' => esc_html__( 'Typography', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]',
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_border_padding',
			[
				'label' => esc_html__( 'Padding', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_style_margin',
			[
				'label' => esc_html__( 'Margin', 'elementskit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'ekit_contact_form_button_style_use_width_height',
			[
				'label' => esc_html__( 'Use Height Width', 'elementskit-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit-lite' ),
				'label_off' => esc_html__( 'Hide', 'elementskit-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_width',
			[
				'label' => esc_html__( 'Width', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_contact_form_button_style_use_width_height' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_style_height',
			[
				'label' => esc_html__( 'Height', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_contact_form_button_style_use_width_height' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_style_line_height',
			[
				'label' => esc_html__( 'Line Height', 'elementskit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_contact_form_button_style_use_width_height' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
            'ekit_contact_form_button_normal_and_hover_tabs'
        );
        $this->start_controls_tab(
            'ekit_contact_form_button_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'elementskit-lite' ),
            ]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_color',
			[
				'label' => esc_html__( 'Color', 'elementskit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_contact_form_button_background',
				'label' => esc_html__( 'Background', 'elementskit-lite' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]',
				'exclude' => ['image'] // PHPCS:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_contact_form_button_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_contact_form_button_border',
				'label' => esc_html__( 'Border', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]',
			]
		);

		$this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'ekit_contact_form_button_title_shadow',
                'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]' ,
            ]
		);

		$this->end_controls_tab();
        $this->start_controls_tab(
            'ekit_contact_form_button_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'elementskit-lite' ),
            ]
		);

		$this->add_responsive_control(
			'ekit_contact_form_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'elementskit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ekit-form form input[type="submit"]:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_contact_form_button_hover_background',
				'label' => esc_html__( 'Background', 'elementskit-lite' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]:hover',
				'exclude' => ['image'] // PHPCS:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_contact_form_button_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_contact_form_button_border_hover',
				'label' => esc_html__( 'Border', 'elementskit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]:hover',
			]
		);

		$this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'ekit_contact_form_button_title_shadow_hover',
                'selector' => '{{WRAPPER}} .ekit-form form input[type="submit"]:hover' ,
            ]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->insert_pro_message();
    }

    protected function render( ) {
        echo '<div class="ekit-wid-con" >';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {

        $settings = $this->get_settings();
		$calderaform_attributes = [
            'id' => $settings['ekit_caldera_form_list'],
        ];
        $this->add_render_attribute( 'shortcode', $calderaform_attributes );
		?>
		<div class="ekit-form">
			<div class="caldera-form-wrap">
				<?php
				if ( !$settings['ekit_caldera_form_list'] ) {
					echo '<div class="elementskit-notices"><p>'.esc_html__('Please select a Contact Form From Setting!', 'elementskit-lite').'</p></div>';
				}else{
					echo do_shortcode( sprintf( '[caldera_form %s]', $this->get_render_attribute_string( 'shortcode' ) ) );
				}
				?>
			</div>
		</div>
		<?php
	}
}
