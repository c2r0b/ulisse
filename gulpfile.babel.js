import $ from 'gulp'

import pump from 'pump'
import argv from 'yargs'
import del from 'del'
import fontAwesome from 'node-font-awesome'
import uglifyify from 'uglifyify'
import babelify from 'babelify'
import compass from 'compass-importer'
import PHPServer from 'php-built-in-server'
import browserSync from 'browser-sync'
import sassInlineImage from 'sass-inline-image'

// gulp-* plugins handler
$.p = require('gulp-load-plugins')()

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
  'fonts'
]

const scriptTasks = [
  'loginScripts',
  'teacherScripts',
  'adminScripts',
  'studentScripts'
]

if (argv.teacher) {
  tasks.push('teacher')
  scriptTasks.push('teacherScripts')
}
else if (argv.admin) {
  tasks.push('admin')
  scriptTasks.push('adminScripts')
}
else if (argv.student) {
  tasks.push('student')
  scriptTasks.push('studentScripts')
}

// shortcut for pump with only source and destination without using filestream
let exportFiles = (src, dest) => {
  pump([
    $.src(src),
    $.dest(dest)
  ])
}

// GENERAL PURPOSE FUNCTIONS ___________________________________________________

// generate app js file for
function scripts(dir, dir_dest) {
  pump(
    [
      $.src('src/' + dir + '/index.js'),
      $.p.bro({
        transform: [
          babelify.configure( { presets: ['env'] } ),
          production ? [ 'uglifyify', { global: true } ] : ''
        ]
      }),
      $.p.rename('app.min.js'),
      $.dest(dest + '/' + (dir_dest || dir.charAt(0))),
      bSync.stream()
    ]
  )
}

// html to pug templates
function templates(dir, dir_dest) {
  pump(
    [
      $.src('src/' + dir + '/**/*.pug'),
      $.p.pug(),
      $.dest(dest + '/' + (dir_dest || dir.charAt(0))),
      bSync.stream()
    ],
    () => console.error.bind(console)
  )
}

// GENERAL PURPOSE TASKS _______________________________________________________

// compile sass using compass
$.task('styles', () => {
  pump(
    [
      $.src('src/sass/*.sass'),
      $.p.sass(
        {
          importer: compass,
          outputStyle: argv.production ? 'compressed' : 'nested',
          functions: sassInlineImage({}),
          includePaths: [fontAwesome.scssPath]
        }
      ),
      $.dest(dest + '/css'),
      bSync.stream()
    ],
    () => $.p.sass.logError
  )
})

// font awesome
$.task('fonts', exportFiles(fontAwesome.fonts, dest + '/fonts'))

// general purpose templates
$.task('templates', templates('templates', 'templates'))

// PHP APIs
$.task('api', exportFiles(['./api/**/*'], dest + '/api'))

// generate translations angular module
$.task('translations', () => {
  pump(
    [
      $.src('src/locale/*.json'),
      $.p.angularTranslate(
        {
          module: 'ulisseTranslations'
        }
      ),
      $.dest('src/temp'),
      bSync.stream()
    ]
  )
})

// STUDENT _____________________________________________________________________

$.task('studentTemplates', templates('student'))

$.task('studentScripts', ['translations','studentTemplates'], scripts('student'))

$.task('student', ['studentScripts', 'studentTemplates'])

// TEACHER _____________________________________________________________________

$.task('teacherTemplates', templates('teacher'))

$.task('teacherScripts', ['translations','teacherTemplates'], scripts('teacher'))

$.task('teacher', ['teacherScripts','teacherTemplates'])

// ADMIN _______________________________________________________________________

$.task('adminTemplates', templates('admin'))

$.task('adminScripts', ['translations', 'adminTemplates'], scripts('admin'))

$.task('admin', ['adminScripts', 'adminTemplates'])

// LOGIN _______________________________________________________________________

$.task('loginTemplates', templates('login', 'login'))

$.task('loginScripts', ['translations'], scripts('login', 'login'))

$.task('login', ['loginScripts', 'loginTemplates'])

// DEFAULT TASKS _______________________________________________________________

// watch for changes
$.task('watch', () => {

  if (argv.student) {
    $.watch('src/student/**/*.js', ['studentScripts']);
    $.watch('src/student/**/*.pug', ['studentTemplates']);
  }
  else if (argv.teacher) {
    $.watch('src/teacher/**/*.js', ['teacherScripts']);
    $.watch('src/teacher/**/*.pug', ['teacherTemplates']);
  }
  else if (argv.admin) {
    $.watch('src/admin/**/*.js', ['adminScripts']);
    $.watch('src/admin/**/*.pug', ['adminTemplates']);
  }

  $.watch('src/login/**/*.js', ['loginScripts']);
  $.watch('src/login/**/*.pug', ['loginTemplates']);

  $.watch('src/templates/**/*.pug', ['templates']);

  $.watch('src/directives/**/*.js', ['scripts']);
  $.watch('src/filters/**/*.js', ['scripts']);
  $.watch('src/services/**/*.js', ['scripts']);

  $.watch('src/locale/*.json', ['translations', 'scripts']);

  $.watch('src/sass/**/*.sass', ['styles']);
  $.watch('img/**/*', ['styles']);
});

$.task('scripts', scriptTasks)

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

// main task
$.task('default', tasks, () => {
  // if in development mode start PHP server and changes watchers
  if (!production)
    $.start(['server', 'watch'])

  // delete temp files
  del(['src/temp/'])

  // create redirect to login
  return $.p.file(
      'index.html',
      '<meta http-equiv="refresh" content="0;URL=login">',
      { src: true }
    )
    .pipe($.dest(dest))
})
