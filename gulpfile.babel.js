import $ from 'gulp'

import argv from 'yargs'
import fontAwesome from 'node-font-awesome'
import babelify from 'babelify'
import compass from 'compass-importer'
import PHPServer from 'php-built-in-server'
import browserSync from 'browser-sync'

// gulp-* plugins handler
$.p = require('gulp-load-plugins')()

import dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass(dartSass);

// production mode indicator
const production = argv.argv.production

// create browser sync instance in development mode
if (!production) var bSync = browserSync.create()

// use 'dist' folder for production output, 'build' otherwise
const dest = production ? 'dist' : 'build'

// tasks to be executed during build
const tasks = [
  'templates',
  'login',
  'styles',
  'fonts',
  'redirect',
  'teacher',
  'admin',
  'student',
  'api'
];

const scriptTasks = [
  'loginScripts',
  'teacherScripts',
  'adminScripts',
  'studentScripts',
  'teacherScripts',
  'adminScripts',
  'studentScripts'
]

// shortcut for pump with only source and destination without using filestream
let exportFiles = (src, dest) => {
  return $.src(src)
    .pipe($.dest(dest));
}

// GENERAL PURPOSE FUNCTIONS ___________________________________________________

// generate app js file for
function scripts(dir, dir_dest) {
  return $.src('src/' + dir + '/index.js')
    .pipe($.p.bro({
      transform: [
        babelify.configure( { presets: ['@babel/preset-env'] } ),
        production ? [ 'uglifyify', { global: true } ] : ''
      ]
    }))
    .pipe($.p.rename('app.min.js'))
    .pipe($.dest(dest + '/' + (dir_dest || dir.charAt(0))))
    .pipe(bSync.stream());
}

// html to pug templates
function templates(dir, dir_dest) {
  return $.src('src/' + dir + '/**/*.pug')
    .pipe($.p.pug())
    .pipe($.dest(dest + '/' + (dir_dest || dir.charAt(0))))
    .pipe(bSync.stream());
}

// GENERAL PURPOSE TASKS _______________________________________________________

// compile sass using compass
$.task('styles', () => {
  return $.src('src/sass/*.sass')
    .pipe(sass(
      {
        importer: compass,
        outputStyle: 'compressed',
        includePaths: [fontAwesome.scssPath]
      }
    ))
    .pipe($.dest(dest + '/css'))
    .pipe(bSync.stream());
})

// font awesome
$.task('fonts', () => exportFiles(fontAwesome.fonts, dest + '/fonts'))

// general purpose templates
$.task('templates', () => templates('templates', 'templates'))

// PHP APIs
$.task('api', () => exportFiles(['./api/**/*'], dest + '/api'))

// generate translations angular module
$.task('translations', () => {
   return $.src('src/locale/*.json')
      .pipe($.p.angularTranslate(
        {
          module: 'ulisseTranslations'
        }
      ))
      .pipe($.dest('temp'))
      .pipe(bSync.stream());
})

// STUDENT _____________________________________________________________________

$.task('studentTemplates', () => templates('student'))

$.task('studentScripts', $.series('translations','studentTemplates'), () => scripts('student'))

$.task('student', $.series('studentScripts', 'studentTemplates'))

// TEACHER _____________________________________________________________________

$.task('teacherTemplates', () => templates('teacher'))

$.task('teacherScripts', $.series('translations','teacherTemplates'), () => scripts('teacher'))

$.task('teacher', $.series('teacherScripts','teacherTemplates'))

// ADMIN _______________________________________________________________________

$.task('adminTemplates', () => templates('admin'))

$.task('adminScripts', $.series('translations', 'adminTemplates'), () => scripts('admin'))

$.task('admin', $.series('adminScripts', 'adminTemplates'))

// LOGIN _______________________________________________________________________

$.task('loginTemplates', () => templates('login', 'login'))

$.task('loginScripts', $.series('translations'), () => scripts('login', 'login'))

$.task('login', $.series('loginScripts', 'loginTemplates'))

// DEFAULT TASKS _______________________________________________________________

// watch for changes
$.task('watch', () => {

  $.watch('src/student/**/*.js', $.series('studentScripts'));
  $.watch('src/student/**/*.pug', $.series('studentTemplates'));
  
  $.watch('src/teacher/**/*.js', $.series('teacherScripts'));
  $.watch('src/teacher/**/*.pug', $.series('teacherTemplates'));
  
  $.watch('src/admin/**/*.js', $.series('adminScripts'));
  $.watch('src/admin/**/*.pug', $.series('adminTemplates'));
  
  $.watch('src/login/**/*.js', $.series('loginScripts'));
  $.watch('src/login/**/*.pug', $.series('loginTemplates'));

  $.watch('src/templates/**/*.pug', $.series('templates'));

  $.watch('src/directives/**/*.js', $.series('scripts'));
  $.watch('src/filters/**/*.js', $.series('scripts'));
  $.watch('src/services/**/*.js', $.series('scripts'));

  $.watch('src/locale/*.json', $.series('translations', 'scripts'));

  $.watch('src/sass/**/*.sass', $.series('styles'));
  $.watch('img/**/*', $.series('styles'));
});

$.task('scripts', $.series(...scriptTasks))

// browser autoreload
$.task('browser-sync', () => {
  browserSync.init({
    proxy: "127.0.0.1:8010"
  })
})

let server = new PHPServer()

$.task('server', () => {
  server.on('listening', event => {
  	console.log('[LISTENING]', event.host.address + ':' + event.host.port)
    $.start(['browser-sync'])
  })

  server.on('error', event => {
  	//console.log('[ERROR]', event.error.toString());
  })

  server.listen('build', 8010, '127.0.0.1')
})

$.task('redirect', () => {
  return $.p.file(
      'index.html',
      '<meta http-equiv="refresh" content="0;URL=login/">',
      { src: true }
    )
    .pipe($.dest(dest));
});

$.task('api', () => {
  return $.src(['api/**/*'])
    .pipe($.dest('build/api'));
});

// main task
$.task('default', $.series(
  $.parallel(...tasks),
  $.parallel('watch', 'server')
)); 