all:
	@echo 'Run "make release" to build release'

monorepo-builder:
	composer create-project symplify/monorepo-builder $@
