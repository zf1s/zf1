VERSION := 1.13.3

all:
	@echo 'Run "make release" to build release'

release: bump-interdependency

bump-interdependency: monorepo-builder
	monorepo-builder/bin/monorepo-builder bump-interdependency "^$(VERSION)"

monorepo-builder:
	composer create-project symplify/monorepo-builder $@
