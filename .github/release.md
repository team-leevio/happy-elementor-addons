name: Release Happy Elementor Addons

on:
  push:
    branches:
      - release

permissions:
  contents: write

concurrency:
  group: happy-release
  cancel-in-progress: false

env:
  NODE_VERSION: "20"
  PLUGIN_FILE: plugin.php
  SVN_REPOSITORY: https://plugins.svn.wordpress.org/happy-elementor-addons
  SECOND_REPOSITORY: HappyMonsters/happy-elementor-addons

jobs:

  release:

    runs-on: ubuntu-latest

    steps:

    ###########################################################
    # Checkout
    ###########################################################

    - name: Checkout Repository
      uses: actions/checkout@v4
      with:
        fetch-depth: 0

    ###########################################################
    # Node
    ###########################################################

    - name: Setup Node
      uses: actions/setup-node@v4
      with:
        node-version: ${{ env.NODE_VERSION }}
        cache: npm

    ###########################################################
    # Install dependencies
    ###########################################################

    - name: Install Packages
      run: npm ci

    ###########################################################
    # Read plugin version
    ###########################################################

    - name: Read Plugin Version
      id: plugin

      shell: bash

      run: |

        VERSION=$(grep -i "^Version:" "${{ env.PLUGIN_FILE }}" | head -1 | sed 's/Version:[[:space:]]*//I' | tr -d '\r')

        if [ -z "$VERSION" ]; then
          echo "Unable to read plugin version."
          exit 1
        fi

        echo "VERSION=$VERSION" >> $GITHUB_ENV

        echo "version=$VERSION" >> $GITHUB_OUTPUT

        echo "Plugin Version : $VERSION"

    ###########################################################
    # Read package.json version
    ###########################################################

    - name: Read Package Version

      shell: bash

      run: |

        PACKAGE_VERSION=$(node -p "require('./package.json').version")

        echo "PACKAGE_VERSION=$PACKAGE_VERSION" >> $GITHUB_ENV

        echo "Package Version : $PACKAGE_VERSION"

    ###########################################################
    # Validate versions
    ###########################################################

    - name: Validate Version

      shell: bash

      run: |

        if [ "$VERSION" != "$PACKAGE_VERSION" ]; then

            echo "plugin.php : $VERSION"

            echo "package.json : $PACKAGE_VERSION"

            echo "Version mismatch."

            exit 1

        fi

    ###########################################################
    # Build
    ###########################################################

    - name: Build Plugin

      run: npm run build

    ###########################################################
    # Create ZIP
    ###########################################################

    - name: Build ZIP

      run: npm run zip

    ###########################################################
    # Locate ZIP
    ###########################################################

    - name: Locate ZIP

      shell: bash

      run: |

        ZIP_FILE="dist/happy-elementor-addons-v${VERSION}.zip"

        if [ ! -f "$ZIP_FILE" ]; then
            echo "ZIP file not found."
            exit 1
        fi

        echo "ZIP_FILE=$ZIP_FILE" >> $GITHUB_ENV

        echo "ZIP : $ZIP_FILE"

    ###########################################################
    # Upload artifact
    ###########################################################

    - name: Upload ZIP

      uses: actions/upload-artifact@v4

      with:

        name: happy-elementor-addons-${{ env.VERSION }}

        path: ${{ env.ZIP_FILE }}

    ###########################################################
    # Install tools
    ###########################################################

    - name: Install SVN

      run: |

        sudo apt-get update

        sudo apt-get install unzip subversion rsync -y

    ###########################################################
    # Checkout SVN
    ###########################################################

    - name: Checkout SVN

      run: |

        svn checkout \
        "${{ env.SVN_REPOSITORY }}" \
        svn

    ###########################################################
    # Extract ZIP
    ###########################################################

    - name: Extract ZIP

      shell: bash

      run: |

        mkdir extracted

        unzip \
          "$ZIP_FILE" \
          -d extracted

        PLUGIN_FOLDER=$(find extracted -mindepth 1 -maxdepth 1 -type d | head -1)

        if [ -z "$PLUGIN_FOLDER" ]; then
            echo "Unable to locate extracted plugin."
            exit 1
        fi

        echo "PLUGIN_FOLDER=$PLUGIN_FOLDER" >> $GITHUB_ENV

        echo "Plugin Folder : $PLUGIN_FOLDER"


            ###########################################################
    # Clean SVN trunk
    ###########################################################

    - name: Clean SVN Trunk
      shell: bash
      run: |
        find svn/trunk -mindepth 1 -delete

    ###########################################################
    # Copy Plugin To SVN
    ###########################################################

    - name: Copy Plugin
      shell: bash
      run: |
        rsync -a \
          --delete \
          "${PLUGIN_FOLDER}/" \
          svn/trunk/

    ###########################################################
    # SVN Remove Deleted Files
    ###########################################################

    - name: SVN Remove Deleted
      shell: bash
      run: |
        cd svn

        svn status | awk '/^\!/ {print $2}' | while read file
        do
          svn delete "$file"
        done

    ###########################################################
    # SVN Add New Files
    ###########################################################

    - name: SVN Add New Files
      shell: bash
      run: |
        cd svn

        svn add trunk \
          --force \
          --auto-props \
          --parents

    ###########################################################
    # SVN Status
    ###########################################################

    - name: SVN Status
      run: |
        cd svn
        svn status

    ###########################################################
    # Check Existing SVN Tag
    ###########################################################

    - name: Check Existing SVN Tag
      shell: bash
      run: |

        cd svn

        if svn ls tags | grep -q "^${VERSION}/$"; then
          echo "SVN tag ${VERSION} already exists."
          exit 1
        fi

    ###########################################################
    # Create SVN Tag
    ###########################################################

    - name: Create SVN Tag
      shell: bash
      run: |

        cd svn

        svn copy \
          "${SVN_REPOSITORY}/trunk" \
          "${SVN_REPOSITORY}/tags/${VERSION}" \
          -m "Tag ${VERSION}" \
          --username "${{ secrets.SVN_USERNAME }}" \
          --password "${{ secrets.SVN_PASSWORD }}" \
          --non-interactive

    ###########################################################
    # Commit To WordPress.org
    ###########################################################

    - name: Commit SVN
      shell: bash
      run: |

        cd svn

        svn commit \
          --username "${{ secrets.SVN_USERNAME }}" \
          --password "${{ secrets.SVN_PASSWORD }}" \
          --non-interactive \
          --trust-server-cert \
          -m "Release version ${VERSION}"

    ###########################################################
    # Update Working Copy
    ###########################################################

    - name: SVN Update
      shell: bash
      run: |

        cd svn

        svn update

    ###########################################################
    # Final SVN Status
    ###########################################################

    - name: Final SVN Status
      shell: bash
      run: |

        cd svn

        svn status

    ###########################################################
    # Configure Git
    ###########################################################

    - name: Configure Git
      run: |

        git config --global user.name "github-actions[bot]"

        git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"

    ###########################################################
    # Check Existing Git Tag
    ###########################################################

    - name: Check Git Tag
      shell: bash
      run: |

        if git rev-parse "v${VERSION}" >/dev/null 2>&1; then
          echo "Git tag v${VERSION} already exists."
          exit 1
        fi

    ###########################################################
    # Create Git Tag (Current Repository)
    ###########################################################

    - name: Create Git Tag
      shell: bash
      run: |

        git tag -a "v${VERSION}" \
          -m "Release v${VERSION}"

    ###########################################################
    # Push Git Tag
    ###########################################################

    - name: Push Git Tag
      run: |

        git push origin refs/tags/v${VERSION}


      ###########################################################
    # Checkout Second Repository
    ###########################################################

    - name: Checkout HappyMonsters Repository
      uses: actions/checkout@v4
      with:
        repository: ${{ env.SECOND_REPOSITORY }}
        token: ${{ secrets.SECOND_REPO_TOKEN }}
        path: second-repo
        fetch-depth: 0

    ###########################################################
    # Sync Repository
    ###########################################################

    - name: Sync Files To Second Repository
      shell: bash
      run: |

        rsync -av --delete \
          --exclude=".git" \
          --exclude=".github" \
          --exclude="node_modules" \
          --exclude="svn" \
          --exclude="extracted" \
          ./ second-repo/

    ###########################################################
    # Commit Second Repository
    ###########################################################

    - name: Commit Second Repository
      shell: bash
      run: |

        cd second-repo

        git config user.name "github-actions[bot]"
        git config user.email "41898282+github-actions[bot]@users.noreply.github.com"

        git add .

        if git diff --cached --quiet; then
          echo "No changes to commit."
        else
          git commit -m "Release ${VERSION}"
        fi

    ###########################################################
    # Push Second Repository
    ###########################################################

    - name: Push Second Repository
      shell: bash
      run: |

        cd second-repo

        git push origin HEAD:main

    ###########################################################
    # Create Second Repository Tag
    ###########################################################

    - name: Create Second Repository Tag
      shell: bash
      run: |

        cd second-repo

        if git rev-parse "v${VERSION}" >/dev/null 2>&1; then
          echo "Tag already exists."
        else
          git tag -a "v${VERSION}" -m "Release v${VERSION}"
          git push origin refs/tags/v${VERSION}
        fi

    ###########################################################
    # Create Draft GitHub Release
    ###########################################################

    - name: Create Draft Release
      uses: softprops/action-gh-release@v2
      with:
        tag_name: v${{ env.VERSION }}
        name: Happy Elementor Addons v${{ env.VERSION }}
        draft: true
        generate_release_notes: true
        files: |
          ${{ env.ZIP_FILE }}
      env:
        GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}

    ###########################################################
    # Workflow Summary
    ###########################################################

    - name: Release Summary
      run: |
        echo "-----------------------------------------"
        echo "Release Completed Successfully"
        echo ""
        echo "Version : ${VERSION}"
        echo "ZIP     : ${ZIP_FILE}"
        echo "SVN     : Released"
        echo "Git Tag : v${VERSION}"
        echo "Release : Draft Created"
        echo "-----------------------------------------"