var gulp = require('gulp');

var config = require('../../gulp-config.json');
var extPath = '.';

// Dependencies
var browserSync = require('browser-sync');
var minifyCSS   = require('gulp-minify-css');
var rename      = require('gulp-rename');
var less        = require('gulp-less');
var changed     = require('gulp-changed');
var rm          = require('gulp-rimraf');
var zip         = require('gulp-zip');

var baseTask  = 'components.com_xpert_testimonials';
var extPath   = './src';
var mediaPath = extPath + '/media';

// Clean
gulp.task('clean:' + baseTask,
	[
		'clean:' + baseTask + ':installscript',
		'clean:' + baseTask + ':xml',
		'clean:' + baseTask + ':componentsadmin',
		'clean:' + baseTask + ':componentssite',
		'clean:' + baseTask + ':langadmin',
		'clean:' + baseTask + ':langsite',
		'clean:' + baseTask + ':media'
	],
	function() {
		return true;
});
// Copy: com
gulp.task('clean:' +  baseTask + ':installscript', function() {
	return gulp.src(config.wwwDir + '/administrator/components/com_xpert_testimonials/script.php', { read: false })
				.pipe(rm({ force: true }));
});
gulp.task('clean:' +  baseTask + ':xml', function() {
	return gulp.src(config.wwwDir + '/administrator/components/com_xpert_testimonials/xml.php', { read: false })
				.pipe(rm({ force: true }));
});

// Clean administrator
gulp.task('clean:' + baseTask + ':componentsadmin', function() {
	return gulp.src(config.wwwDir + '/administrator/components/com_xpert_testimonials', { read: false })
				.pipe(rm({ force: true }));
});
// Clean site
gulp.task('clean:' + baseTask + ':componentssite', function() {
	return gulp.src(config.wwwDir + '/components/com_xpert_testimonials', { read: false })
				.pipe(rm({ force: true }));
});
// Clean: site languages
gulp.task('clean:' + baseTask + ':langsite', function() {
	return gulp.src(config.wwwDir + '/language/**/*.com_xpert_testimonials.*')
		.pipe(rm({ force: true }));
});
// Clean: admin languages
gulp.task('clean:' + baseTask + ':langadmin', function() {
	return gulp.src(config.wwwDir + '/administrator/language/**/*.com_xpert_testimonials.*')
		.pipe(rm({ force: true }));
});
// Clean Media
gulp.task('clean:' + baseTask + ':media', function() {
	return gulp.src(config.wwwDir + '/media/com_xpert_testimonials', { read: false })
				.pipe(rm({ force: true }));
});

// Copy
gulp.task('copy:' + baseTask,
	[
		'copy:' + baseTask + ':installscript',
		'copy:' + baseTask + ':xml',
		'copy:' + baseTask + ':componentsadmin',
		'copy:' + baseTask + ':componentssite',
		'copy:' + baseTask + ':langadmin',
		'copy:' + baseTask + ':langsite',
		'copy:' + baseTask + ':media'
	],
	function() {
});

// Copy: com
gulp.task('copy:' +  baseTask + ':installscript', ['clean:' + baseTask + ':installscript'], function() {
	return gulp.src(extPath + '/script.php')
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_xpert_testimonials'));
});
gulp.task('copy:' +  baseTask + ':xml', ['clean:' + baseTask + ':xml'], function() {
	return gulp.src(extPath + '/xpert_testimonials.xml')
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_xpert_testimonials'));
});

// Copy: admin component
gulp.task('copy:' +  baseTask + ':componentsadmin', ['clean:' + baseTask + ':componentsadmin'], function() {
	return gulp.src(extPath + '/admin/**')
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_xpert_testimonials'));
});

// Copy: component
gulp.task('copy:' +  baseTask + ':componentssite', ['clean:' + baseTask + ':componentssite'], function() {
	return gulp.src(extPath + '/site/**')
		.pipe(gulp.dest(config.wwwDir + '/components/com_xpert_testimonials'));
});
// Copy: admin languages
gulp.task('copy:' +  baseTask + ':langadmin', ['clean:' + baseTask + ':langadmin'], function() {
	return gulp.src(extPath + '/admin/language/**')
		.pipe(gulp.dest(config.wwwDir + '/administrator/language'));
});
// Copy: site languages
gulp.task('copy:' +  baseTask + ':langsite', ['clean:' + baseTask + ':langsite'], function() {
	return gulp.src(extPath + '/site/language/**')
		.pipe(gulp.dest(config.wwwDir + '/language'));
});
// Copy: media
gulp.task('copy:' +  baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
	return gulp.src(mediaPath + '/**')
		.pipe(gulp.dest(config.wwwDir + '/media/com_xpert_testimonials'));
});

// less
gulp.task('less:' + baseTask, function () {
	return gulp.src(mediaPath + '/less/style.less')
		.pipe(less({loadPath: [mediaPath + '/less']}))
		.pipe(gulp.dest(mediaPath + '/css'))
		.pipe(gulp.dest(config.wwwDir + '/media/com_xpert_testimonials/css'))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
				path.basename += '.min';
		}))
		.pipe(gulp.dest(mediaPath + '/css'))
		.pipe(gulp.dest(config.wwwDir + '/media/com_xpert_testimonials/css'));
});

// zip
gulp.task('zip:' + baseTask, function () {
	return gulp.src(mediaPath + '/less/style.less')
		.pipe(less({loadPath: [mediaPath + '/less']}))
		.pipe(gulp.dest(mediaPath + '/css'))
		.pipe(gulp.dest(config.wwwDir + '/media/com_xpert_testimonials/css'))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
				path.basename += '.min';
		}))
		.pipe(gulp.dest(mediaPath + '/css'))
		.pipe(gulp.dest(config.wwwDir + '/media/com_xpert_testimonials/css'));
});

// Watch
gulp.task('watch:' + baseTask,
	[
		'watch:' + baseTask + ':installscript',
		'watch:' + baseTask + ':xml',
		'watch:' + baseTask + ':componentsadmin',
		'watch:' + baseTask + ':componentssite',
		'watch:' + baseTask + ':langadmin',
		'watch:' + baseTask + ':langsite',
		'watch:' + baseTask + ':media',
		'watch:' + baseTask + ':less'
	],
	function() {
		return true;
});

// watch: com
gulp.task('watch:' +  baseTask + ':installscript', function() {
		gulp.watch(extPath + '/script.php', ['copy:' + baseTask + ':installscript', browserSync.reload]);
});
gulp.task('watch:' +  baseTask + ':xml', function() {
		gulp.watch(extPath + '/xpert_testimonials.xml', ['copy:' + baseTask + ':xml', browserSync.reload]);
});

// watch: admin component
gulp.task('watch:' +  baseTask + ':componentsadmin', function() {
		gulp.watch(extPath + '/admin/**', ['copy:' + baseTask + ':componentsadmin', browserSync.reload]);
});
// Copy: component
gulp.task('watch:' +  baseTask + ':componentssite', function() {
	gulp.watch(extPath + '/site/**', ['copy:' + baseTask + ':componentssite', browserSync.reload]);
});

// Watch: Languagesadmin
gulp.task('watch:' + baseTask + ':langadmin', function() {
	gulp.watch(extPath + '/admin/language/**', ['copy:' + baseTask + ':langadmin', browserSync.reload]);
});
// Watch: Languages site
gulp.task('watch:' + baseTask + ':langsite', function() {
	gulp.watch(extPath + '/language/**', ['copy:' + baseTask + ':langsite', browserSync.reload]);
});

// Watch: media
gulp.task('watch:' + baseTask + ':media', function() {
	gulp.watch(mediaPath + '/**', ['copy:' + baseTask + ':media', browserSync.reload]);
});
	// Watch: Styles
gulp.task('watch:' + baseTask + ':less', function() {
		gulp.watch(mediaPath + '/less/**', ['less:' + baseTask + ':less', browserSync.reload]);
});