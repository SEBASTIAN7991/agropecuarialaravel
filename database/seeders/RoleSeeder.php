<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;//se agrego
use Spatie\Permission\Models\Permission;//se agrego

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SuperAdministrador = Role::create(['name' =>'SuperAdministrador']);
        $Administrador = Role::create(['name' =>'Administrador']);
        $Beneficiarios = Role::create(['name' =>'Beneficiarios']);
        $Oficios = Role::create(['name' =>'Oficios']);
        $FierroMarcador = Role::create(['name' =>'FierroMarcador']);
        $Visitas = Role::create(['name' =>'Visitas']);
        $Inventario = Role::create(['name' =>'Inventario']);

        Permission::create(['name' => 'admin.users'])->syncRoles([$SuperAdministrador]);
        Permission::create(['name' => 'admin.users.edit'])->syncRoles([$SuperAdministrador]);
        Permission::create(['name' => 'admin.areas.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.cargos.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.organizaciones.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.regiones.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.proyectos.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.localidades.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.solicitudes.index'])->syncRoles([$SuperAdministrador,$Administrador]);
        Permission::create(['name' => 'admin.validaciones.index'])->syncRoles([$SuperAdministrador]);
        Permission::create(['name' => 'admin.beneficiarios.index'])->syncRoles([$SuperAdministrador,$Beneficiarios]);
        Permission::create(['name' => 'admin.beneficiarios.destroy'])->syncRoles([$SuperAdministrador]);
        Permission::create(['name' => 'admin.oficios.index'])->syncRoles([$SuperAdministrador,$Oficios]);
        Permission::create(['name' => 'admin.fierros.index'])->syncRoles([$SuperAdministrador,$FierroMarcador]);
        Permission::create(['name' => 'admin.visitas.index'])->syncRoles([$SuperAdministrador,$Visitas]);
        Permission::create(['name' => 'admin.inventarios.index'])->syncRoles([$SuperAdministrador,$Inventario]);
    }
}
