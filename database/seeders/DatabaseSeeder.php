<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $attributes = [
            [
                'name' => 'size',
                'frontend_label' => 'Size',
                'frontend_input' => 'dropdown',
                'used_for_variations' => true,
            ],
            [
                'name' => 'color',
                'frontend_label' => 'Color',
                'frontend_input' => 'color_picker',
                'used_for_variations' => true,
            ],
        ];

        foreach ($attributes as $attribute) {
            \App\Models\Attribute::create($attribute);
        }

        $attributeValues = [
            [
                'attribute_id' => 1,
                'value' => 'small',
                'label' => 'S'
            ],
            [
                'attribute_id' => 1,
                'value' => 'medium',
                'label' => 'M'
            ],
            [
                'attribute_id' => 1,
                'value' => 'large',
                'label' => 'L'
            ],
            [
                'attribute_id' => 2,
                'value' => '#FF0000',
                'label' => 'Red'
            ],
            [
                'attribute_id' => 2,
                'value' => '#000000',
                'label' => 'Black'
            ],
        ];

        foreach ($attributeValues as $value) {
            \App\Models\AttributeValue::create($value);
        }
    }
}