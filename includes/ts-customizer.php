<?php

/* custom Customizer controls used only by plugin support
/**
 * @param        $label
 * @param string $description
 *
 * @return array
 */
function absolute_weaver_cz_css( $label, $description = '' ) {
	return array(
		'setting' => array( 'sanitize_callback' => 'absolute_weaver_cz_sanitize_css', 'transport' => 'postMessage', 'default' => '' ),
		'control' => array(
			'control_type' => 'Absolute_Weaver_Textarea_Control',
			'label'        => $label,
			'description'  => $description,
			'type'         => 'textarea',
			'input_attrs'  => array(
				'rows'        => '2',
				'placeholder' => __( '{font-size:150%;font-weight:bold;} /* for example */', 'absolute-weaver' ),
			),
		),
	);
}
