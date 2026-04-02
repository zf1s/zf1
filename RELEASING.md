### How to Release

1. Bump interdependencies of packages to the next version

    ```bash
    php bin/bump-deps.php <version>
    ```

    Use `php bin/bump-deps.php --detect` to check the current version.

2. Update `CHANGELOG.md` with the new version entry
    (suggestion: use an LLM to draft the changelog from `git log` since the last tag)

3. Commit, tag and push

    ```bash
    git add -A && git commit -m "release <version>"
    git tag <version>
    git push && git push --tags
    ```

4. Run the [release workflow](https://github.com/zf1s/zf1/actions/workflows/release.yml) with the tag as input:

    ```bash
    gh workflow run release.yml -f tag=<version>
    ```

    Or via [GitHub UI](https://github.com/zf1s/zf1/actions/workflows/release.yml): Run workflow → enter the tag.

    This splits all packages to their individual repos and creates a GitHub release
    with notes composed from `CHANGELOG.md` and auto-detected contributors.
