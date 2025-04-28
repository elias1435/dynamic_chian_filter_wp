<?php
/**
 * Plugin Name: Hotel Filter Form
 * Description: Adds a hotel filter search form with destination, holiday type, and facilities.
 * Version: 1.0
 * Author: Md. Elias
 * Author URI: https://buildwithelias.tech/
 */

add_shortcode('hotel_filter_form', 'hotel_filter_form_shortcode');
add_action('wp_enqueue_scripts', 'hotel_filter_form_assets');

// Enqueue Scripts
function hotel_filter_form_assets() {
	
	// Enqueue CSS
    wp_enqueue_style('hotel-filter-form-style', plugin_dir_url(__FILE__) . 'assets/css/filter.css');
	
    wp_enqueue_script('hotel-filter-ajax', plugin_dir_url(__FILE__) . 'assets/js/filter.js', ['jquery'], null, true);
    wp_localize_script('hotel-filter-ajax', 'hotelFilterAjax', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);

}

// Shortcode Output
function hotel_filter_form_shortcode() {
    ob_start();
    ?>
    <form id="hotel-filter-form" action="<?php echo site_url('/'); ?>" method="get">
        <!-- Destinations -->
        <select id="destinations" name="destinations">
            <option value="">Destination</option>
            <?php
            $parent_terms = get_terms([
                'taxonomy' => 'zones',
                'parent' => 0,
                'hide_empty' => true,
            ]);
            foreach ($parent_terms as $term) {
                echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . ' (' . $term->count . ')</option>';
            }
            ?>
        </select>

        <!-- Sub Destinations -->
        <select id="sub-destinations" name="sub_destinations">
            <option value="">Sub Destination</option>
        </select>


		<!-- Holiday Type (Single Select) -->
		<select id="holiday-type" name="holiday_type" class="filter-field" disabled>
			<option value="">Holiday Type</option>
		</select>

		<!-- Facilities (Always Visible but Initially Disabled) -->
		<div class="facilities-dropdown-wrapper disabled" id="facilities-wrapper">
			<div class="fc-label">Facilities <span class="fc-caret">&#9662;</span></div>
			<div class="facilities-dropdown">
				<input type="text" id="facilities-search" placeholder="Search Facilities" disabled>
				<div class="facilities-options">
					<!-- Options will be loaded dynamically -->
				</div>
			</div>
			<!-- Spinner for facilities loading -->
			<div id="facilities-spinner" style="display:none; text-align:center; margin:10px 0;">
				<img src="https://i.imgur.com/llF5iyg.gif" alt="Loading..." width="24" height="24">
			</div>
		</div>


        <!-- Submit -->
        <button type="submit">Search</button>
        <input type="hidden" name="post_type" value="hotel">
    </form>
    <?php
    return ob_get_clean();
}

// AJAX handler for loading Sub Destination terms
add_action('wp_ajax_load_sub_zones', 'hotel_filter_load_sub_zones');
add_action('wp_ajax_nopriv_load_sub_zones', 'hotel_filter_load_sub_zones');

function hotel_filter_load_sub_zones() {
    $parent_id = intval($_POST['parent_id']);
    $terms = get_terms([
        'taxonomy' => 'zones',
        'parent' => $parent_id,
        'hide_empty' => true,
    ]);

    echo '<option value="">Select Sub Destination</option>';
    foreach ($terms as $term) {
        echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . ' (' . $term->count . ')</option>';
    }
    wp_die();
}


// Modify search query to handle custom filters
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && (is_search() || is_post_type_archive('hotel'))) {
        
        // Only run for our filter form (post_type=hotel)
        if (isset($_GET['post_type']) && $_GET['post_type'] == 'hotel') {
            $tax_query = [];

            // Destinations (Parent)
            if (!empty($_GET['destinations'])) {
                $tax_query[] = [
                    'taxonomy' => 'zones',
                    'field' => 'term_id',
                    'terms' => [intval($_GET['destinations'])],
                ];
            }

            // Sub Destination (Child)
            if (!empty($_GET['sub_destinations'])) {
                $tax_query[] = [
                    'taxonomy' => 'zones',
                    'field' => 'term_id',
                    'terms' => [intval($_GET['sub_destinations'])],
                ];
            }

            // Holiday Type
            if (!empty($_GET['holiday_type'])) {
                $holiday_types = is_array($_GET['holiday_type']) ? array_map('intval', $_GET['holiday_type']) : [intval($_GET['holiday_type'])];
                $tax_query[] = [
                    'taxonomy' => 'recommend',
                    'field' => 'term_id',
                    'terms' => $holiday_types,
                ];
            }

            // Facilities
            if (!empty($_GET['facilities'])) {
                $facilities = is_array($_GET['facilities']) ? array_map('intval', $_GET['facilities']) : [intval($_GET['facilities'])];
                $tax_query[] = [
                    'taxonomy' => 'facilities-services',
                    'field' => 'term_id',
                    'terms' => $facilities,
                    'operator' => 'AND', // Must have ALL selected facilities
                ];
            }

            if (!empty($tax_query)) {
                $query->set('tax_query', $tax_query);
            }

            // Set correct post_type
            $query->set('post_type', 'hotel');
        }
    }
});

// Load Holiday Types based on Sub Destination, with dynamic count
add_action('wp_ajax_load_holiday_types', 'load_holiday_types_callback');
add_action('wp_ajax_nopriv_load_holiday_types', 'load_holiday_types_callback');

function load_holiday_types_callback() {
    $sub_id = intval($_POST['sub_id']);

    if ($sub_id) {
        // Find Hotels under selected Sub Destination
        $hotel_ids = get_posts([
            'post_type' => 'hotel',
            'numberposts' => -1,
            'fields' => 'ids',
            'tax_query' => [
                [
                    'taxonomy' => 'zones',
                    'field' => 'term_id',
                    'terms' => [$sub_id],
                ],
            ],
        ]);

        if (!empty($hotel_ids)) {
            $holiday_terms = get_terms([
                'taxonomy' => 'recommend',
                'orderby' => 'name',
                'hide_empty' => false,
            ]);

            if (!empty($holiday_terms)) {
                echo '<option value="">Select Holiday Type</option>';
                foreach ($holiday_terms as $term) {
                    // Now count only hotels matching this Holiday Type + Sub Destination
                    $related_hotels = get_posts([
                        'post_type' => 'hotel',
                        'numberposts' => -1,
                        'fields' => 'ids',
                        'tax_query' => [
                            [
                                'taxonomy' => 'zones',
                                'field' => 'term_id',
                                'terms' => [$sub_id],
                            ],
                            [
                                'taxonomy' => 'recommend',
                                'field' => 'term_id',
                                'terms' => [$term->term_id],
                            ],
                        ],
                    ]);

                    $count = count($related_hotels);

                    if ($count > 0) {
                        echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . ' (' . $count . ')</option>';
                    }
                }
            } else {
                echo '<option value="">No options found</option>';
            }
        } else {
            echo '<option value="">No options found</option>';
        }
    } else {
        echo '<option value="">No options found</option>';
    }

    wp_die();
}


// Load Facilities dynamically based on Holiday Type + Destination + Sub Destination
add_action('wp_ajax_load_facilities', 'load_facilities_callback');
add_action('wp_ajax_nopriv_load_facilities', 'load_facilities_callback');

function load_facilities_callback() {
    $holiday_id = intval($_POST['holiday_id']);
    $destination_id = intval($_POST['destination_id']);
    $subdestination_id = intval($_POST['subdestination_id']);

    if ($holiday_id) {
        // Build dynamic tax_query
        $tax_query = [
            'relation' => 'AND',
            [
                'taxonomy' => 'recommend',
                'field' => 'term_id',
                'terms' => [$holiday_id],
            ],
        ];

        if (!empty($destination_id)) {
            $tax_query[] = [
                'taxonomy' => 'zones',
                'field' => 'term_id',
                'terms' => [$destination_id],
            ];
        }

        if (!empty($subdestination_id)) {
            $tax_query[] = [
                'taxonomy' => 'zones',
                'field' => 'term_id',
                'terms' => [$subdestination_id],
            ];
        }

        // Find Hotels matching filters
        $hotel_ids = get_posts([
            'post_type' => 'hotel',
            'numberposts' => -1,
            'fields' => 'ids',
            'tax_query' => $tax_query,
        ]);

        if (!empty($hotel_ids)) {
            $facility_terms = wp_get_object_terms($hotel_ids, 'facilities-services', ['orderby' => 'name']);

            if (!empty($facility_terms)) {
                $counts = [];

                foreach ($facility_terms as $term) {
                    if (!isset($counts[$term->term_id])) {
                        $counts[$term->term_id] = 1;
                    } else {
                        $counts[$term->term_id]++;
                    }
                }

                $all_facilities = get_terms([
                    'taxonomy' => 'facilities-services',
                    'orderby' => 'name',
                    'hide_empty' => false,
                ]);

                foreach ($all_facilities as $term) {
                    if (isset($counts[$term->term_id])) {
                        $id = 'facility_' . $term->term_id;
                        echo '<div class="facility-option">';
                        echo '<input type="checkbox" id="' . esc_attr($id) . '" name="facilities[]" value="' . esc_attr($term->term_id) . '">';
                        echo '<label for="' . esc_attr($id) . '"> ' . esc_html($term->name) . ' (' . $counts[$term->term_id] . ')</label>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<div>No options found</div>';
            }
        } else {
            echo '<div>No options found</div>';
        }
    } else {
        echo '<div>No options found</div>';
    }

    wp_die();
}
