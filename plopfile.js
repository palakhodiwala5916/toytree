/**
 * generator/index.js
 *
 * Exports the generators so plop knows them
 */

const fs = require('fs');
const path = require('path');
const ecrGenerator = require('./scripts/generator/generators/ecr');
const populateRequestBodyGenerator = require('./scripts/generator/generators/populate-request-body');
const pluralize = require('pluralize');

/**
 * Every generated backup file gets this extension
 * @type {string}
 */
const BACKUPFILE_EXTENSION = 'rbgen';

function pathCase(value) {
    if (typeof value === 'string') {
        value = value.split('/')
            .map(a => {
                if (a) {
                    return a[0].toUpperCase() + a.slice(1);
                }
                return a;
            })
            .join('/')
    }
    return value
}

function plural(value) {
    if (typeof value === 'string') {
        return pluralize(value);

        if (value.slice(value.length - 1) === 'y') {
            return `${value.slice(0, value.length - 1)}ies`;
        }

        if (value.slice(value.length - 1) === 's') {
            return `${value}es`;
        }

        return `${value}s`;
    }

    return value
}

function tableNameCase(value) {
    if (typeof value !== 'string') {
        return value;
    }
    const str = value
        .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
        .map(x => x.toLowerCase())
        .join('_');

    return plural(str);
}

function lowerCase(value) {
    return (value || '').toLowerCase();
}

function isAllowedType(value) {
    let found = ['string', 'int', 'array', 'DateTime', 'boolean', 'bool'].find(t => t === value);
    if (found) {
        if (found === 'DateTime') {
            return 'int'
        }
        return found
    }
}

module.exports = plop => {
    plop.setHelper('pathCase', pathCase);
    plop.setHelper('plural', plural);
    plop.setHelper('tableNameCase', tableNameCase);
    plop.setHelper('lowercase', lowerCase);
    plop.setHelper('isAllowedType', isAllowedType);

    plop.addHelper('directory', comp => {
        return __dirname;
    });
    plop.addHelper('curly', (object, open) => (open ? '{' : '}'));

    plop.setGenerator('ecr', ecrGenerator);
    plop.setGenerator('populate-request-body', populateRequestBodyGenerator);
};

module.exports.BACKUPFILE_EXTENSION = BACKUPFILE_EXTENSION;
