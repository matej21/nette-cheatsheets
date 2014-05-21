module.exports = function (grunt) {


	grunt.initConfig({

		shell: {
			options: {
				stdout: true
			},
			composerInstall: {
				command: "composer install"
			},
			composerDumpAutoload: {
				command: "composer dump-autoload -o"
			},
			ensureChmod: {
				command: "chmod -R 777 temp log www/webtemp www/pdf"
			},
			generatePdf: {
				command: "php www/index.php app:createPdf"
			},
			bowerInstall: {
				command: "bower install"
			}
		},
		fontello: {
			cheatsheets: {
				options: {
					config: './www/assets/fontello/config.json',
					fonts: 'www/dist/tmp',
					styles: 'www/dist/tmp',
					force: true
				}
			}
		},
		less: {
			cheatsheets: {
				options: {
					compress: true
				},
				files: {
					"./www/dist/tmp/cheatsheets.css": "./www/assets/stylesheets/cheatsheets.less"
				}
			},
			web: {
				options: {
					compress: true
				},
				files: {
					"./www/dist/css/web.css": "./www/assets/stylesheets/web.less"
				}
			},
			webCheatsheets: {
				options: {
					compress: true
				},
				files: {
					"./www/dist/css/cheatsheets-web.css": "./www/assets/stylesheets/cheatsheets-web.less"
				}
			}
		},
		cssmin: {
			cheatsheets: {
				files: {
					'www/dist/css/cheatsheets.css': ['www/dist/tmp/fontello-out.css', 'www/dist/tmp/cheatsheets.css']
				}
			}
		},
		copy: {
			fontello: {
				src: "www/dist/tmp/fontello-embedded.css",
				dest: "www/dist/tmp/fontello-out.css",
				options: {
					process: function(content) {
						return content.replace(/\s*src:(\s*url\(\'\.\.\/[^)]+\'\)\s*(format\([^)]+\))?\s*[,;])+/gm, ""); //remove external fonts
					}
				}
			},
			neon: {
				src: "app/config/config.local.dist.neon",
				dest: "app/config/config.local.neon",
				filter: function() {
					return !grunt.file.exists("app/config/config.local.neon");
				}
			}
		},
		clean: {
			netteCache: ['temp/cache'],
			tempAssets: ['www/dist/tmp'],
			webtemp: ['www/webtemp']
		}
	});
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-fontello');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('initialize', ['shell:composerInstall', 'shell:composerDumpAutoload', 'shell:ensureChmod', 'copy:neon']);
	grunt.registerTask('cheatsheetsAssets', ['shell:bowerInstall', 'less:cheatsheets', 'fontello:cheatsheets', 'copy:fontello', 'cssmin:cheatsheets', 'clean:tempAssets']);
	grunt.registerTask('webAssets', ['shell:bowerInstall', 'less:web']);
	grunt.registerTask('generatePdf', ['shell:generatePdf']);
	grunt.registerTask('default', ["initialize", "cheatsheetsAssets", "webAssets", 'generatePdf']);
};