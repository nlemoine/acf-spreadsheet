<?php

namespace HelloNico\AcfSpreadsheet;

class SpreadsheetField extends \acf_field
{
    use Concerns\Asset;

    /**
     * The field name.
     *
     * @var string
     */
    public $name = 'spreadsheet';

    /**
     * The field label.
     *
     * @var string
     */
    public $label = 'Spreadsheet';

    /**
     * The field category.
     *
     * @var string
     */
    public $category = 'jquery';

    /**
     * The field defaults.
     *
     * @var array
     */
    public $defaults = [
        'return_format' => 'string',
    ];

    /**
     * Create a new field instance.
     *
     * @param  string $uri
     * @param  string $path
     * @return void
     */
    public function __construct($uri, $path)
    {
        $this->uri = $uri;
        $this->path = $path;

        parent::__construct();
    }

    /**
     * The rendered field type.
     *
     * @param  array $field
     * @return void
     */
    public function render_field($field)
    {
        $data = $field['value'];
        $data_count = is_array($data) ? count($data) : 3;
        ?>
        <?php $this->get_table_placeholder($data_count, $field['id']); ?>

        <div class="acf-spreadsheet-control"></div>
        <?php
            $field['value'] = json_encode($field['value']);
            $field['class'] = $field['class'] . ' acf-spreadsheet-input acf-hidden';
			$atts  = [];
			$keys  = ['id', 'class', 'name', 'value'];
			// atts (value="123")
			foreach ( $keys as $k ) {
				if ( isset( $field[ $k ] ) ) {
					$atts[ $k ] = $field[ $k ];
				}
			}

			// remove empty atts
			$atts = acf_clean_atts( $atts );

			// return
			acf_textarea_input( $atts );
    }

    private function get_table_placeholder(int $rows, string $id) {
?>
<svg
  role="img"
  width="100%"
  height="<?php echo $rows * 35; ?>"
  aria-labelledby="loading-aria"
  preserveAspectRatio="none"
>
  <title id="loading-aria">Loading...</title>
  <rect
    x="0"
    y="0"
    width="100%"
    height="100%"
    clip-path="url(#clip-path-<?= $id ?>)"
    style='fill: url("#fill-<?= $id ?>");'
  ></rect>
  <defs>
    <clipPath id="clip-path-<?= $id ?>">
        <?php for($i = 1; $i <= $rows; $i++): ?>
            <rect x="0" y="<?php echo $i === 1 ? 0 : (($i - 1) * 35) ?>" rx="5" ry="5" width="100%" height="25" />
        <?php endfor; ?>
    </clipPath>
    <linearGradient id="fill-<?= $id ?>">
      <stop
        offset="0.599964"
        stop-color="#f3f3f3"
        stop-opacity="1"
      >
        <animate
          attributeName="offset"
          values="-2; -2; 1"
          keyTimes="0; 0.25; 1"
          dur="2s"
          repeatCount="indefinite"
        ></animate>
      </stop>
      <stop
        offset="1.59996"
        stop-color="#ecebeb"
        stop-opacity="1"
      >
        <animate
          attributeName="offset"
          values="-1; -1; 2"
          keyTimes="0; 0.25; 1"
          dur="2s"
          repeatCount="indefinite"
        ></animate>
      </stop>
      <stop
        offset="2.59996"
        stop-color="#f3f3f3"
        stop-opacity="1"
      >
        <animate
          attributeName="offset"
          values="0; 0; 3"
          keyTimes="0; 0.25; 1"
          dur="2s"
          repeatCount="indefinite"
        ></animate>
      </stop>
    </linearGradient>
  </defs>
</svg>
<?php
    }

    /**
     * The rendered field type settings.
     *
     * @param  array $field
     * @return void
     */
    public function render_field_settings($field)
    {
    }

    /**
     * The formatted field value.
     *
     * @param  mixed $value
     * @param  int   $post_id
     * @param  array $field
     * @return mixed
     */
    public function format_value($value, $post_id, $field)
    {
        return $value;
    }

    /**
     * The condition the field value must meet before
     * it is valid and can be saved.
     *
     * @param  bool  $valid
     * @param  mixed $value
     * @param  array $field
     * @param  array $input
     * @return bool
     */
    public function validate_value($valid, $value, $field, $input)
    {
        return $valid;
    }

    /**
     * The field value after loading from the database.
     *
     * @param  mixed $value
     * @param  int   $post_id
     * @param  array $field
     * @return mixed
     */
    public function load_value($value, $post_id, $field)
    {
        return json_decode(json_encode(maybe_unserialize($value)));
    }

    /**
     * The field value before saving to the database.
     *
     * @param  mixed $value
     * @param  int   $post_id
     * @param  array $field
     * @return mixed
     */
    public function update_value($value, $post_id, $field)
    {
        if(is_array($value)) {
            return $value;
        }
        $value_decoded = \json_decode(wp_unslash($value), true);
        if(is_array($value_decoded)) {
            $value = array_values(array_filter($value_decoded, function($row) {
                return !empty(array_filter($row));
            }));
        }

        return empty($value) ? null : $value;
    }

    /**
     * The action fired when deleting a field value from the database.
     *
     * @param  int    $post_id
     * @param  string $key
     * @return void
     */
    public function delete_value($post_id, $key)
    {
        // delete_value($post_id, $key);
    }

    /**
     * The field after loading from the database.
     *
     * @param  array $field
     * @return array
     */
    public function load_field($field)
    {
        return $field;
    }

    /**
     * The field before saving to the database.
     *
     * @param  array $field
     * @return array
     */
    public function update_field($field)
    {
        return $field;
    }

    /**
     * The action fired when deleting a field from the database.
     *
     * @param  array $field
     * @return void
     */
    public function delete_field($field)
    {
        // parent::delete_field($field);
    }

    /**
     * The assets enqueued when rendering the field.
     *
     * @return void
     */
    public function input_admin_enqueue_scripts()
    {
        wp_register_style('jsuites', 'https://jsuites.net/v4/jsuites.css', [], null);
        wp_register_style('jsuites-icons', 'https://fonts.googleapis.com/css?family=Open+Sans|Roboto|Material+Icons', [], null);
        wp_enqueue_style($this->name, $this->asset('field.css'), ['jsuites', 'jsuites-icons'], null);
        wp_enqueue_script($this->name, $this->asset('field.js'), ['jquery'], null, true);
    }

    /**
     * The assets enqueued when creating a field group.
     *
     * @return void
     */
    public function field_group_admin_enqueue_scripts()
    {
        $this->input_admin_enqueue_scripts();
    }
}

