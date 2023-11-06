/**
 * Entity Controller Repository Generator
 */

/* eslint strict: ["off"] */

'use strict';
const {spawnSync} = require('child_process');


module.exports = {
    description: 'Create Request Body for specific entity based on entity props',
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
            name: 'confirmationSetters',
            default: true,
            message: 'Dou you want to write object setters service -> updateObjectFields?',
        },
        {
            type: 'confirm',
            name: 'confirmation',
            default: true,
            message: 'This action will overwrite the existing Request body VO file?',
        },
    ],
    actions: (data) => {
        if (!data.confirmation) {
            return [];
        }
        const templateDir = './scripts/generator/generators/populate-request-body/';
        const srcDir = '{{directory}}/src';
        const relativePath = '{{pathCase relativePath}}/{{properCase entityName}}';
        const actions = [];
        const namespaceParts = data.classname.split('\\');

        if (namespaceParts[0] === 'App') {
            namespaceParts.shift();
        }
        if (namespaceParts[0] === 'Entity') {
            namespaceParts.shift();
        }


        data['entityName'] = [...namespaceParts].pop();
        data['relativePath'] = namespaceParts.slice(0, namespaceParts.length - 1).join('\\');

        const results = spawnSync('php', ['bin/console', 'app:dump:entity-schema', data['classname']]);

        // if (results.error) {
        console.log(results.output.toString())
        // console.log(results.error)
        // console.log(results.stderr)
        // }
        data['proprieties'] = JSON.parse(results.stdout.toString());

        // console.log(data)

        actions.push({
            type: 'add',
            force: true,
            path: `${srcDir}/VO/Protocol/Api/${relativePath}Body.php`,
            templateFile: `${templateDir}request_body.js.hbs`,
            abortOnFail: true,
        });

        actions.push({
            type: 'modify',
            path: `${srcDir}/Service/Data/${relativePath}Service.php`,
            pattern: new RegExp("// TODO: Implement updateObjectFields\\(\\) method\\."),
            templateFile: `${templateDir}service_getters.js.hbs`,
            abortOnFail: false
        });

        return actions;
    },
};
