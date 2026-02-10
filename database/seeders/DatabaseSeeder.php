<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manufacturer;
use App\Models\Component;
use App\Models\Assembly;
use App\Models\AssemblyComponent;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add users
        $users = [
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => '123456', 'is_admin' => true,],
            ['name' => 'John Doe', 'email' => 'john@gmail.com', 'password' => '123456',],
            ['name' => 'Jane Smith', 'email' => 'jane@gmail.com', 'password' => '123456',],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Add manufacturers
        $manufacturerA = Manufacturer::create(['name' => 'Manufacturer A']);
        $manufacturerB = Manufacturer::create(['name' => 'Manufacturer B']);


        // Add components
        $componentsData = [
            ['name' => 'Motor A', 'type' => 'Motor', 'price' => 120.50, 'manufacturer_id' => $manufacturerA->id],
            ['name' => 'Gearbox B', 'type' => 'Gearbox', 'price' => 85.75, 'manufacturer_id' => $manufacturerA->id],
            ['name' => 'Sensor C', 'type' => 'Sensor', 'price' => 45.00, 'manufacturer_id' => $manufacturerB->id],
            ['name' => 'Bolt D', 'type' => 'Fastener', 'price' => 0.50, 'manufacturer_id' => $manufacturerB->id],
            ['name' => 'Panel E', 'type' => 'Panel', 'price' => 30.00, 'manufacturer_id' => $manufacturerB->id],
            ['name' => 'Cable F', 'type' => 'Cable', 'price' => 5.25, 'manufacturer_id' => $manufacturerA->id],
        ];

        $components = [];
        foreach ($componentsData as $data) {
            $components[$data['name']] = Component::create($data);
        }

        // Add assemblies
        $assembliesData = [
            ['name' => 'Robot Arm', 'price' => 500.00],
            ['name' => 'Conveyor Belt', 'price' => 300.00],
            ['name' => 'Sensor Module', 'price' => 150.00],
        ];

        $assemblies = [];
        foreach ($assembliesData as $data) {
            $assemblies[$data['name']] = Assembly::create($data);
        }

        // Assemblies data connection
        $assemblyComponentsData = [
            ['assembly' => 'Robot Arm', 'component' => 'Motor A', 'location' => 'A1'],
            ['assembly' => 'Robot Arm', 'component' => 'Gearbox B', 'location' => 'A2'],
            ['assembly' => 'Robot Arm', 'component' => 'Bolt D', 'location' => 'A3'],
            ['assembly' => 'Robot Arm', 'component' => 'Cable F', 'location' => 'A4'],
            ['assembly' => 'Conveyor Belt', 'component' => 'Gearbox B', 'location' => 'B1'],
            ['assembly' => 'Conveyor Belt', 'component' => 'Panel E', 'location' => 'B2'],
            ['assembly' => 'Conveyor Belt', 'component' => 'Cable F', 'location' => 'B3'],
            ['assembly' => 'Sensor Module', 'component' => 'Sensor C', 'location' => 'C1'],
            ['assembly' => 'Sensor Module', 'component' => 'Bolt D', 'location' => 'C2'],
        ];

        foreach ($assemblyComponentsData as $ac) {
            AssemblyComponent::create([
                'assembly_id' => $assemblies[$ac['assembly']]->id,
                'component_id' => $components[$ac['component']]->id,
                'location' => $ac['location'],
            ]);
        }
    }
}
