<?php

return [
    'Permission' => [
        'FIELDS' => [
            'dashboard_system_status' => [
                'partition' => 'dashboard',
                'label' => 'Ver Estado del Sistema',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 1
            ],
            'dashboard_clientes' => [
                'partition' => 'dashboard',
                'label' => 'Clientes',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 2
            ],
            'dashboard_payroll' => [
                'partition' => 'dashboard',
                'label' => 'Finanza',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 3
            ],
            'dashboard_enrutador' => [
                'partition' => 'dashboard',
                'label' => 'Enrutadores',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 4
            ],

            //planes
            'plan_internet' => [
                'partition' => 'plan',
                'label' => 'Internet',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'plan_view_internet' => [
                        'field' => 'plan_view_internet',
                        'label' => 'Ver Listado de Planes de Internet',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'plan_add_internet' => [
                        'field' => 'plan_add_internet',
                        'label' => 'Agregar Plan de Internet',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'plan_edit_internet' => [
                        'field' => 'plan_edit_internet',
                        'label' => 'Editar Plan de Internet',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'plan_delete_internet' => [
                        'field' => 'plan_delete_internet',
                        'label' => 'Eliminar Plan de Internet',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 5
            ],
            'plan_view_internet' => [
                'include' => false,
            ],
            'plan_add_internet' => [
                'include' => false,
            ],
            'plan_edit_internet' => [
                'include' => false,
            ],
            'plan_delete_internet' => [
                'include' => false,
            ],

            'plan_voz' => [
                'partition' => 'plan',
                'label' => 'Voz',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'plan_view_voz' => [
                        'field' => 'plan_view_voz',
                        'label' => 'Ver Listado de Planes de Voz',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'plan_add_voz' => [
                        'field' => 'plan_add_voz',
                        'label' => 'Agregar Plan de Voz',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'plan_edit_voz' => [
                        'field' => 'plan_edit_voz',
                        'label' => 'Editar Plan de Voz',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'plan_delete_voz' => [
                        'field' => 'plan_delete_voz',
                        'label' => 'Eliminar Plan de Voz',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 6
            ],
            'plan_view_voz' => [
                'include' => false,
            ],
            'plan_add_voz' => [
                'include' => false,
            ],
            'plan_edit_voz' => [
                'include' => false,
            ],
            'plan_delete_voz' => [
                'include' => false,
            ],

            'plan_custom' => [
                'partition' => 'plan',
                'label' => 'Custom',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'plan_view_custom' => [
                        'field' => 'plan_view_custom',
                        'label' => 'Ver Listado de Planes Customs',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'plan_add_custom' => [
                        'field' => 'plan_add_custom',
                        'label' => 'Agregar Plan Custom',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'plan_edit_custom' => [
                        'field' => 'plan_edit_custom',
                        'label' => 'Editar Plan Custom',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'plan_delete_custom' => [
                        'field' => 'plan_delete_custom',
                        'label' => 'Eliminar Plan Custom',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 6
            ],
            'plan_view_custom' => [
                'include' => false,
            ],
            'plan_add_custom' => [
                'include' => false,
            ],
            'plan_edit_custom' => [
                'include' => false,
            ],
            'plan_delete_custom' => [
                'include' => false,
            ],

            'plan_paquetes' => [
                'partition' => 'plan',
                'label' => 'Paquete',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'plan_view_paquetes' => [
                        'field' => 'plan_view_paquetes',
                        'label' => 'Ver Listado de Planes de Paquetes',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'plan_add_paquetes' => [
                        'field' => 'plan_add_paquetes',
                        'label' => 'Agregar Plan de Paquete',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'plan_edit_paquetes' => [
                        'field' => 'plan_edit_paquetes',
                        'label' => 'Editar Plan de Paquete',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'plan_delete_paquetes' => [
                        'field' => 'plan_delete_paquetes',
                        'label' => 'Eliminar Plan de Paquete',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 6
            ],
            'plan_view_paquetes' => [
                'include' => false,
            ],
            'plan_add_paquetes' => [
                'include' => false,
            ],
            'plan_edit_paquetes' => [
                'include' => false,
            ],
            'plan_delete_paquetes' => [
                'include' => false,
            ],

            //Crm
            'crm_dashboard' => [
                'partition' => 'crm',
                'label' => 'Ver Dashboard del crm',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 1
            ],
            'crm_crm' => [
                'partition' => 'crm',
                'label' => 'Crm',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'crm_view_crm' => [
                        'field' => 'crm_view_crm',
                        'label' => 'Ver Listado de Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'crm_add_crm' => [
                        'field' => 'crm_add_crm',
                        'label' => 'Agregar Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'crm_edit_crm' => [
                        'field' => 'crm_edit_crm',
                        'label' => 'Editar Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'crm_delete_crm' => [
                        'field' => 'crm_delete_crm',
                        'label' => 'Eliminar Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 6
                    ],
                ]),
                'position' => 6
            ],
            'crm_view_crm' => [
                'include' => false,
            ],
            'crm_add_crm' => [
                'include' => false,
            ],
            'crm_edit_crm' => [
                'include' => false,
            ],
            'crm_delete_crm' => [
                'include' => false,
            ],

            'crm_information' => [
                'partition' => 'crm',
                'label' => 'Informacion del Crm',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'crm_information_view_tab_crm' => [
                        'field' => 'crm_information_view_tab_crm',
                        'label' => 'Ver Pestaña de Informacion del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'crm_information_geolocation_crm' => [
                        'field' => 'crm_information_geolocation_crm',
                        'label' => 'Ver Geo Localizacion del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                ]),
                'position' => 7
            ],
            'crm_information_view_tab_crm' => [
                'include' => false,
            ],
            'crm_information_geolocation_crm' => [
                'include' => false,
            ],

            'crm_document' => [
                'partition' => 'crm',
                'label' => 'Documentos del Crm',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'crm_document_view_tab_crm' => [
                        'field' => 'crm_document_view_tab_crm',
                        'label' => 'Ver Pestaña de Documentos del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'crm_document_view_crm' => [
                        'field' => 'crm_document_view_crm',
                        'label' => 'Ver Listado de Documentos del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'crm_document_add_crm' => [
                        'field' => 'crm_document_add_crm',
                        'label' => 'Agregar Documento al Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'crm_document_edit_crm' => [
                        'field' => 'crm_document_edit_crm',
                        'label' => 'Editar Documento Subido al Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'crm_document_delete_crm' => [
                        'field' => 'crm_document_delete_crm',
                        'label' => 'Eliminar Documento Subido al Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 6
                    ],
                ]),
                'position' => 8
            ],
            'crm_document_view_tab_crm' => [
                'include' => false,
            ],
            'crm_document_view_crm' => [
                'include' => false,
            ],
            'crm_document_add_crm' => [
                'include' => false,
            ],
            'crm_document_edit_crm' => [
                'include' => false,
            ],
            'crm_document_delete_crm' => [
                'include' => false,
            ],


            //Client
            'client_dashboard' => [
                'partition' => 'client',
                'label' => 'Ver Dashboard del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox',
                'value' => false,
                'position' => 1
            ],
            'client_client' => [
                'partition' => 'client',
                'label' => 'Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_view_client' => [
                        'field' => 'client_view_client',
                        'label' => 'Ver Listado de Clientes',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_add_client' => [
                        'field' => 'client_add_client',
                        'label' => 'Agregar Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'client_edit_client' => [
                        'field' => 'client_edit_client',
                        'label' => 'Editar Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'client_delete_client' => [
                        'field' => 'client_delete_client',
                        'label' => 'Eliminar Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 2
            ],
            'client_view_client' => [
                'include' => false,
            ],
            'client_add_client' => [
                'include' => false,
            ],
            'client_edit_client' => [
                'include' => false,
            ],
            'client_delete_client' => [
                'include' => false,
            ],

            'client_information' => [
                'partition' => 'client',
                'label' => 'Informacion del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_information_view_tab_client' => [
                        'field' => 'client_information_view_tab_client',
                        'label' => 'Ver Pestaña de Informacion del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_information_geolocation_client' => [
                        'field' => 'client_information_geolocation_client',
                        'label' => 'Ver Geo Localizacion del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                ]),
                'position' => 3
            ],
            'client_information_view_tab_client' => [
                'include' => false,
            ],
            'client_information_geolocation_client' => [
                'include' => false,
            ],

            'client_service' => [
                'partition' => 'client',
                'label' => 'Servicio del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_service_view_tab_client' => [
                        'field' => 'client_service_view_tab_client',
                        'label' => 'Ver Pestaña de Servicio del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ]
                ]),
                'position' => 4
            ],
            'client_service_view_tab_client' => [
                'include' => false,
            ],

            'client_service_internet' => [
                'partition' => 'client',
                'label' => 'Servicio de Internet del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_service_internet_view_client' => [
                        'field' => 'client_service_internet_view_client',
                        'label' => 'Ver Listado de Servicio de Internet del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_service_internet_add_client' => [
                        'field' => 'client_service_internet_add_client',
                        'label' => 'Agregar Servicio de Internet al Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'client_service_internet_edit_client' => [
                        'field' => 'client_service_internet_edit_client',
                        'label' => 'Editar Servicio de Internet del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'client_service_internet_delete_client' => [
                        'field' => 'client_service_internet_delete_client',
                        'label' => 'Eliminar Servicio de Internet del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 6
                    ],
                ]),
                'position' => 5
            ],
            'client_service_internet_view_client' => [
                'include' => false,
            ],
            'client_service_internet_add_client' => [
                'include' => false,
            ],
            'client_service_internet_edit_client' => [
                'include' => false,
            ],
            'client_service_internet_delete_client' => [
                'include' => false,
            ],
            'client_service_voz' => [
                'partition' => 'client',
                'label' => 'Servicio de Voz del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_service_voz_view_client' => [
                        'field' => 'client_service_voz_view_client',
                        'label' => 'Ver Listado de Servicio de Voz del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_service_voz_add_client' => [
                        'field' => 'client_service_voz_add_client',
                        'label' => 'Agregar Servicio de Voz al Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'client_service_voz_edit_client' => [
                        'field' => 'client_service_voz_edit_client',
                        'label' => 'Editar Servicio de Voz del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'client_service_voz_delete_client' => [
                        'field' => 'client_service_voz_delete_client',
                        'label' => 'Eliminar Servicio de Voz del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 7
                    ],
                ]),
                'position' => 5
            ],
            'client_service_voz_view_client' => [
                'include' => false,
            ],
            'client_service_voz_add_client' => [
                'include' => false,
            ],
            'client_service_voz_edit_client' => [
                'include' => false,
            ],
            'client_service_voz_delete_client' => [
                'include' => false,
            ],
            'client_service_bundle' => [
                'partition' => 'client',
                'label' => 'Servicio de Paquetes del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_service_bundle_view_client' => [
                        'field' => 'client_service_bundle_view_client',
                        'label' => 'Ver Listado de Servicio de Paquete del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_service_bundle_add_client' => [
                        'field' => 'client_service_bundle_add_client',
                        'label' => 'Agregar Servicio de Paquete al Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'client_service_bundle_edit_client' => [
                        'field' => 'client_service_bundle_edit_client',
                        'label' => 'Editar Servicio de Paquete del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'client_service_bundle_delete_client' => [
                        'field' => 'client_service_bundle_delete_client',
                        'label' => 'Eliminar Servicio de Paquete del Crm',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 6
                    ],
                ]),
                'position' => 5
            ],
            'client_service_bundle_view_client' => [
                'include' => false,
            ],
            'client_service_bundle_add_client' => [
                'include' => false,
            ],
            'client_service_bundle_edit_client' => [
                'include' => false,
            ],
            'client_service_bundle_delete_client' => [
                'include' => false,
            ],

            // TODO falta permiso del tab y crud de payroll

            'client_payroll' => [
                'partition' => 'client',
                'label' => 'Facturacion del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_payroll_view_tab_client' => [
                        'field' => 'client_payroll_view_tab_client',
                        'label' => 'Ver Pestaña de Facturacion del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ]
                ]),
                'position' => 9
            ],
            'client_payroll_view_tab_client' => [
                'include' => false,
            ],

            'client_payroll_payment' => [
                'partition' => 'client',
                'label' => 'Pago del Cliente',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'client_payroll_payment_view_client' => [
                        'field' => 'client_payroll_payment_view_client',
                        'label' => 'Ver Listado de Pagos del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'client_payroll_payment_add_client' => [
                        'field' => 'client_payroll_payment_add_client',
                        'label' => 'Agregar Pago al Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'client_payroll_payment_edit_client' => [
                        'field' => 'client_payroll_payment_edit_client',
                        'label' => 'Editar Pago del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'client_payroll_payment_delete_client' => [
                        'field' => 'client_payroll_payment_delete_client',
                        'label' => 'Eliminar Pago del Cliente',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 6
                    ],
                ]),
                'position' => 5
            ],
            'client_payroll_payment_view_client' => [
                'include' => false,
            ],
            'client_payroll_payment_add_client' => [
                'include' => false,
            ],
            'client_payroll_payment_edit_client' => [
                'include' => false,
            ],
            'client_payroll_payment_delete_client' => [
                'include' => false,
            ],

            //Router
            'router_router' => [
                'partition' => 'router',
                'label' => 'Router',
                'placeholder' => '',
                'type' => 'input-checkbox-with-inputs',
                'value' => false,

                'depend' => true,
                'inputs_depend' => json_encode([
                    'router_view_router' => [
                        'field' => 'router_view_router',
                        'label' => 'Ver Listado de Routers',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 1
                    ],
                    'router_add_router' => [
                        'field' => 'router_add_router',
                        'label' => 'Agregar Router',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 2
                    ],
                    'router_edit_router' => [
                        'field' => 'router_edit_router',
                        'label' => 'Editar router',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 3
                    ],
                    'router_delete_router' => [
                        'field' => 'router_delete_router',
                        'label' => 'Eliminar router',
                        'placeholder' => '',
                        'type' => 'input-checkbox',
                        'value' => false,
                        'position' => 4
                    ],
                ]),
                'position' => 5
            ],
            'router_view_router' => [
                'include' => false,
            ],
            'router_add_router' => [
                'include' => false,
            ],
            'router_edit_router' => [
                'include' => false,
            ],
            'router_delete_router' => [
                'include' => false,
            ],

        ],
    ],
];
