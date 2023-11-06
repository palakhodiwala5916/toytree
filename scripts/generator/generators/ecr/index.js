/**
 * Entity Controller Repository Generator
 */

/* eslint strict: ["off"] */

'use strict';

module.exports = {
    description: 'Create Entity, Controller, Repository, Service',
    prompts: [
        {
            type: 'input',
            name: 'classname',
            message: 'Full or relative entity class namespace: (e.g. App\\Entity\\Foo\\Bar or Foo\\Bar)',
            validate: value => {
                if (!value) {
                    return 'The namespace is required';
                }

                const pattern = 'App\\Entity\\Foo\\Bar or Foo\\Bar';
                const isRoot = value.indexOf('App') === 0;
                const partsCount = value.split('\\').length;

                if (value.indexOf('\\\\') >= 0) {
                    return `Invalid namespace. Expected pattern: ${pattern}`;
                }
                if (isRoot && partsCount <= 2) {
                    return `Entity cannot be on App\\Entity root namespace. Expected pattern: ${pattern}`;
                } else if (partsCount <= 1) {
                    return `Entity cannot be on App\\Entity root namespace. Expected pattern: ${pattern}`;
                }

                return true;
            },
        },
        {
            type: 'confirm',
            name: 'wantAdminController',
            default: true,
            message: 'Do you want create a controller for Admin application?',
        },
        {
            type: 'confirm',
            name: 'wantClientController',
            default: true,
            message: 'Do you want create a controller for Client application?',
        },
    ],
    actions: data => {
        const templateDir = './scripts/generator/generators/ecr/';
        const srcDir = '{{directory}}/src';
        const relativePath = '{{pathCase relativePath}}/{{properCase entityName}}';
        const actions = [];
        const namespaceParts = data.classname.replace(' ', '').split('\\');

        if (namespaceParts[0] === 'App') {
            namespaceParts.shift();
        }
        if (namespaceParts[0] === 'Entity') {
            namespaceParts.shift();
        }

        data['entityName'] = [...namespaceParts].pop();
        data['relativePath'] = namespaceParts.slice(0, namespaceParts.length - 1).join('\\');

        actions.push({
            type: 'add',
            path: `${srcDir}/Entity/${relativePath}.php`,
            templateFile: `${templateDir}entity.js.hbs`,
            abortOnFail: false,
        });

        actions.push({
            type: 'add',
            path: `${srcDir}/Repository/${relativePath}Repository.php`,
            templateFile: `${templateDir}repository.js.hbs`,
            abortOnFail: false,
        });
        actions.push({
            type: 'add',
            path: `${srcDir}/DependencyInjection/Repository/${relativePath}RepositoryDI.php`,
            templateFile: `${templateDir}repository_di.js.hbs`,
            abortOnFail: false,
        });

        actions.push({
            type: 'add',
            path: `${srcDir}/Service/Data/${relativePath}Service.php`,
            templateFile: `${templateDir}service.js.hbs`,
            abortOnFail: false,
        });
        actions.push({
            type: 'add',
            path: `${srcDir}/DependencyInjection/Service/Data/${relativePath}ServiceDI.php`,
            templateFile: `${templateDir}service_di.js.hbs`,
            abortOnFail: false,
        });

        actions.push({
            type: 'add',
            path: `${srcDir}/VO/Protocol/Api/${relativePath}Filters.php`,
            templateFile: `${templateDir}filters.js.hbs`,
            abortOnFail: false,
        });

        actions.push({
            type: 'add',
            path: `${srcDir}/VO/Protocol/Api/${relativePath}Body.php`,
            templateFile: `${templateDir}request_body.js.hbs`,
            abortOnFail: false,
        });

        if (data.wantAdminController) {
            actions.push({
                type: 'add',
                path: `${srcDir}/Controller/Admin/${relativePath}AdminController.php`,
                templateFile: `${templateDir}admin_controller.js.hbs`,
                abortOnFail: false,
            });
        }

        if (data.wantClientController) {
            actions.push({
                type: 'add',
                path: `${srcDir}/Controller/Api/V1/${relativePath}ClientController.php`,
                templateFile: `${templateDir}client_controller.js.hbs`,
                abortOnFail: false,
            });
        }

        return actions;
    },
};
