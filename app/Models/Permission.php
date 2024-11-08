<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    const VIEW_SALES   = 'view sales';
    const CREATE_SALES = 'create sales';
    const UPDATE_SALES = 'update sales';
    const DELETE_SALES = 'delete sales';

    const VIEW_PRODUCTS   = 'view products';
    const CREATE_PRODUCTS = 'create products';
    const UPDATE_PRODUCTS = 'update products';
    const DELETE_PRODUCTS = 'delete products';

    const VIEW_PURCHASES   = 'view purchases';
    const CREATE_PURCHASES = 'create purchases';
    const UPDATE_PURCHASES = 'update purchases';
    const DELETE_PURCHASES = 'delete purchases';

    const VIEW_CUSTOMERS   = 'view customers';
    const CREATE_CUSTOMERS = 'create customers';
    const UPDATE_CUSTOMERS = 'update customers';
    const DELETE_CUSTOMERS = 'delete customers';

    const VIEW_SUPPLIERS   = 'view suppliers';
    const CREATE_SUPPLIERS = 'create suppliers';
    const UPDATE_SUPPLIERS = 'update suppliers';
    const DELETE_SUPPLIERS = 'delete suppliers';

    const VIEW_USERS   = 'view users';
    const CREATE_USERS = 'create users';
    const UPDATE_USERS = 'update users';
    const DELETE_USERS = 'delete users';

    const VIEW_CATEGORIES   = 'view categories';
    const CREATE_CATEGORIES = 'create categories';
    const UPDATE_CATEGORIES = 'update categories';
    const DELETE_CATEGORIES = 'delete categories';

    const VIEW_PERMISSIONS = 'view permissions';
    const CREATE_PERMISSIONS = 'create permissions';
    const UPDATE_PERMISSIONS = 'update permissions';
    const DELETE_PERMISSIONS = 'delete permissions';

    const VIEW_LOCATIONS = 'view locations';
    const CREATE_LOCATIONS = 'create locations';
    const UPDATE_LOCATIONS = 'update locations';
    const DELETE_LOCATIONS = 'delete locations';

    const VIEW_EXPENSES = 'view expenses';
    const CREATE_EXPENSES = 'create expenses';
    const UPDATE_EXPENSES = 'update expenses';
    const DELETE_EXPENSES = 'delete expenses';

    const VIEW_SETTINGS = 'view settings';
    const VIEW_FINANCES = 'view finances';

    const VIEW_REPORTS = 'view reports';
}
