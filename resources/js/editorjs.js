import EditorJS from '@editorjs/editorjs';
import ImageTool from '@editorjs/image';
import List from '@editorjs/list';
import Header from '@editorjs/header';
import Underline from '@editorjs/underline';
import Code from '@editorjs/code';
import InlineCode from '@editorjs/inline-code';
import Quote from '@editorjs/quote';

document.addEventListener('alpine:init', () => {
    Alpine.data('editorjs', (placeholder = "", readOnly = false, logLevel = "ERROR") => ({
        instance: null,
        data: null,
        id: null,

        init() {
            this.id = this.$id('editorjs');

            this.$watch('data', () => {
                if (this.instance !== null) {
                    return;
                }

                this.initEditor();
            });
        },

        initEditor() {
            this.instance = new EditorJS({
                holder: this.id,

                readOnly,

                placeholder,

                logLevel,

                tools: {
                    image: {
                        class: ImageTool,

                        config: {
                            uploader: {
                                uploadByFile: (file) => {
                                    return new Promise((resolve) => {
                                        this.$wire.upload(
                                            'uploads',
                                            file,
                                            (uploadedFilename) => {
                                                const eventName = `fileupload:${uploadedFilename.substr(0, 20)}`;

                                                const storeListener = (event) => {
                                                    resolve({
                                                        success: 1,
                                                        file: {
                                                            url: event.detail.url
                                                        }
                                                    });

                                                    window.removeEventListener(eventName, storeListener);
                                                };

                                                window.addEventListener(eventName, storeListener);

                                                this.$wire.call('completedImageUpload', uploadedFilename, eventName);
                                            }
                                        );
                                    });
                                },

                                uploadByUrl: (url) => {
                                    return this.$wire.loadImageFromUrl(url).then(result => {
                                        return {
                                            success: 1,
                                            file: {
                                                url: result
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    },
                    list: List,
                    header: Header,
                    underline: Underline,
                    code: Code,
                    'inline-code': InlineCode,
                    quote: Quote
                },

                data: this.data,

                onChange: () => {
                    this.instance.save().then((outputData) => {
                        this.data = outputData;
                    }).catch((error) => {
                        console.log('Saving failed: ', error)
                    });
                }
            });
        }
    }));
});
