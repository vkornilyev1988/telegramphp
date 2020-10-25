<?php
    return [
        'local' => [
            'main' => [
                'root' => '{root}/public/storage',
                'dir' => '{dir}',
                'fileName' => '{f}-{name}',

                'rules'=> [
                    'dir' => [
                        'random' => true,
                        'size' => 3,
                        'symbols' => ['0','1','2','3','4','5','6','7','8','9'],
                    ],
                    'f' => [
                        'random' => true,
                        'size' => 3,
                        'symbols' => ['0','1','2','3','4','5','6','7','8','9'],
                    ],
                ],
            ],
            'backups' => [
                'root' => '{root}/backups',
            ],
			'bills' => [
				'root' => '{root}/bills',
			],
            'certs' => [
                'root' => '{root}/certs',
            ]
        ],
    ];
