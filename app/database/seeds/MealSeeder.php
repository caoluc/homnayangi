<?php

class MealSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (($handle = fopen(__DIR__ . '/meal.csv', 'r')) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $row++;
                if ($row === 1) {
                    continue;
                } else {
                    Meal::create([
                        'name' => $data[0],
                        'start_point' => (isset($data[1]) && !empty($data[1])) ? $data[1] : 0,
                        'description' => (isset($data[2]) && !empty($data[2])) ? $data[2] : null,
                        'image' => (isset($data[3]) && !empty($data[3])) ? $data[3] : null,
                    ]);
                }
            }
            fclose($handle);
        }
    }

}
