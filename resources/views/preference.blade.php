<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MultiSafepay Payments - </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        rel="stylesheet"
        href="https://unpkg.com/@shopify/polaris@6.6.0/dist/styles.css"
    />
    <script defer src="https://unpkg.com/alpinejs@3.4.2/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@shopify/app-bridge@2"></script>
    <script src="https://unpkg.com/@shopify/app-bridge-utils@2"></script>
    <script src="https://unpkg.com/@shopify/app-bridge-actions@2"></script>
</head>
<body>

<div
    style="background-color: rgb(246, 246, 247); color: rgb(32, 34, 35); --p-background:rgba(246, 246, 247, 1); --p-background-hovered:rgba(241, 242, 243, 1); --p-background-pressed:rgba(237, 238, 239, 1); --p-background-selected:rgba(237, 238, 239, 1); --p-surface:rgba(255, 255, 255, 1); --p-surface-neutral:rgba(228, 229, 231, 1); --p-surface-neutral-hovered:rgba(219, 221, 223, 1); --p-surface-neutral-pressed:rgba(201, 204, 208, 1); --p-surface-neutral-disabled:rgba(241, 242, 243, 1); --p-surface-neutral-subdued:rgba(246, 246, 247, 1); --p-surface-subdued:rgba(250, 251, 251, 1); --p-surface-disabled:rgba(250, 251, 251, 1); --p-surface-hovered:rgba(246, 246, 247, 1); --p-surface-pressed:rgba(241, 242, 243, 1); --p-surface-depressed:rgba(237, 238, 239, 1); --p-backdrop:rgba(0, 0, 0, 0.5); --p-overlay:rgba(255, 255, 255, 0.5); --p-shadow-from-dim-light:rgba(0, 0, 0, 0.2); --p-shadow-from-ambient-light:rgba(23, 24, 24, 0.05); --p-shadow-from-direct-light:rgba(0, 0, 0, 0.15); --p-hint-from-direct-light:rgba(0, 0, 0, 0.15); --p-surface-search-field:rgba(241, 242, 243, 1); --p-border:rgba(140, 145, 150, 1); --p-border-neutral-subdued:rgba(186, 191, 195, 1); --p-border-hovered:rgba(153, 158, 164, 1); --p-border-disabled:rgba(210, 213, 216, 1); --p-border-subdued:rgba(201, 204, 207, 1); --p-border-depressed:rgba(87, 89, 89, 1); --p-border-shadow:rgba(174, 180, 185, 1); --p-border-shadow-subdued:rgba(186, 191, 196, 1); --p-divider:rgba(225, 227, 229, 1); --p-icon:rgba(92, 95, 98, 1); --p-icon-hovered:rgba(26, 28, 29, 1); --p-icon-pressed:rgba(68, 71, 74, 1); --p-icon-disabled:rgba(186, 190, 195, 1); --p-icon-subdued:rgba(140, 145, 150, 1); --p-text:rgba(32, 34, 35, 1); --p-text-disabled:rgba(140, 145, 150, 1); --p-text-subdued:rgba(109, 113, 117, 1); --p-interactive:rgba(44, 110, 203, 1); --p-interactive-disabled:rgba(189, 193, 204, 1); --p-interactive-hovered:rgba(31, 81, 153, 1); --p-interactive-pressed:rgba(16, 50, 98, 1); --p-focused:rgba(69, 143, 255, 1); --p-surface-selected:rgba(242, 247, 254, 1); --p-surface-selected-hovered:rgba(237, 244, 254, 1); --p-surface-selected-pressed:rgba(229, 239, 253, 1); --p-icon-on-interactive:rgba(255, 255, 255, 1); --p-text-on-interactive:rgba(255, 255, 255, 1); --p-action-secondary:rgba(255, 255, 255, 1); --p-action-secondary-disabled:rgba(255, 255, 255, 1); --p-action-secondary-hovered:rgba(246, 246, 247, 1); --p-action-secondary-pressed:rgba(241, 242, 243, 1); --p-action-secondary-depressed:rgba(109, 113, 117, 1); --p-action-primary:rgba(0, 128, 96, 1); --p-action-primary-disabled:rgba(241, 241, 241, 1); --p-action-primary-hovered:rgba(0, 110, 82, 1); --p-action-primary-pressed:rgba(0, 94, 70, 1); --p-action-primary-depressed:rgba(0, 61, 44, 1); --p-icon-on-primary:rgba(255, 255, 255, 1); --p-text-on-primary:rgba(255, 255, 255, 1); --p-text-primary:rgba(0, 123, 92, 1); --p-text-primary-hovered:rgba(0, 108, 80, 1); --p-text-primary-pressed:rgba(0, 92, 68, 1); --p-surface-primary-selected:rgba(241, 248, 245, 1); --p-surface-primary-selected-hovered:rgba(179, 208, 195, 1); --p-surface-primary-selected-pressed:rgba(162, 188, 176, 1); --p-border-critical:rgba(253, 87, 73, 1); --p-border-critical-subdued:rgba(224, 179, 178, 1); --p-border-critical-disabled:rgba(255, 167, 163, 1); --p-icon-critical:rgba(215, 44, 13, 1); --p-surface-critical:rgba(254, 211, 209, 1); --p-surface-critical-subdued:rgba(255, 244, 244, 1); --p-surface-critical-subdued-hovered:rgba(255, 240, 240, 1); --p-surface-critical-subdued-pressed:rgba(255, 233, 232, 1); --p-surface-critical-subdued-depressed:rgba(254, 188, 185, 1); --p-text-critical:rgba(215, 44, 13, 1); --p-action-critical:rgba(216, 44, 13, 1); --p-action-critical-disabled:rgba(241, 241, 241, 1); --p-action-critical-hovered:rgba(188, 34, 0, 1); --p-action-critical-pressed:rgba(162, 27, 0, 1); --p-action-critical-depressed:rgba(108, 15, 0, 1); --p-icon-on-critical:rgba(255, 255, 255, 1); --p-text-on-critical:rgba(255, 255, 255, 1); --p-interactive-critical:rgba(216, 44, 13, 1); --p-interactive-critical-disabled:rgba(253, 147, 141, 1); --p-interactive-critical-hovered:rgba(205, 41, 12, 1); --p-interactive-critical-pressed:rgba(103, 15, 3, 1); --p-border-warning:rgba(185, 137, 0, 1); --p-border-warning-subdued:rgba(225, 184, 120, 1); --p-icon-warning:rgba(185, 137, 0, 1); --p-surface-warning:rgba(255, 215, 157, 1); --p-surface-warning-subdued:rgba(255, 245, 234, 1); --p-surface-warning-subdued-hovered:rgba(255, 242, 226, 1); --p-surface-warning-subdued-pressed:rgba(255, 235, 211, 1); --p-text-warning:rgba(145, 106, 0, 1); --p-border-highlight:rgba(68, 157, 167, 1); --p-border-highlight-subdued:rgba(152, 198, 205, 1); --p-icon-highlight:rgba(0, 160, 172, 1); --p-surface-highlight:rgba(164, 232, 242, 1); --p-surface-highlight-subdued:rgba(235, 249, 252, 1); --p-surface-highlight-subdued-hovered:rgba(228, 247, 250, 1); --p-surface-highlight-subdued-pressed:rgba(213, 243, 248, 1); --p-text-highlight:rgba(52, 124, 132, 1); --p-border-success:rgba(0, 164, 124, 1); --p-border-success-subdued:rgba(149, 201, 180, 1); --p-icon-success:rgba(0, 127, 95, 1); --p-surface-success:rgba(174, 233, 209, 1); --p-surface-success-subdued:rgba(241, 248, 245, 1); --p-surface-success-subdued-hovered:rgba(236, 246, 241, 1); --p-surface-success-subdued-pressed:rgba(226, 241, 234, 1); --p-text-success:rgba(0, 128, 96, 1); --p-decorative-one-icon:rgba(126, 87, 0, 1); --p-decorative-one-surface:rgba(255, 201, 107, 1); --p-decorative-one-text:rgba(61, 40, 0, 1); --p-decorative-two-icon:rgba(175, 41, 78, 1); --p-decorative-two-surface:rgba(255, 196, 176, 1); --p-decorative-two-text:rgba(73, 11, 28, 1); --p-decorative-three-icon:rgba(0, 109, 65, 1); --p-decorative-three-surface:rgba(146, 230, 181, 1); --p-decorative-three-text:rgba(0, 47, 25, 1); --p-decorative-four-icon:rgba(0, 106, 104, 1); --p-decorative-four-surface:rgba(145, 224, 214, 1); --p-decorative-four-text:rgba(0, 45, 45, 1); --p-decorative-five-icon:rgba(174, 43, 76, 1); --p-decorative-five-surface:rgba(253, 201, 208, 1); --p-decorative-five-text:rgba(79, 14, 31, 1); --p-border-radius-base:0.4rem; --p-border-radius-wide:0.8rem; --p-border-radius-full:50%; --p-card-shadow:0px 0px 5px var(--p-shadow-from-ambient-light), 0px 1px 2px var(--p-shadow-from-direct-light); --p-popover-shadow:-1px 0px 20px var(--p-shadow-from-ambient-light), 0px 1px 5px var(--p-shadow-from-direct-light); --p-modal-shadow:0px 26px 80px var(--p-shadow-from-dim-light), 0px 0px 1px var(--p-shadow-from-dim-light); --p-top-bar-shadow:0 2px 2px -1px var(--p-shadow-from-direct-light); --p-button-drop-shadow:0 1px 0 rgba(0, 0, 0, 0.05); --p-button-inner-shadow:inset 0 -1px 0 rgba(0, 0, 0, 0.2); --p-button-pressed-inner-shadow:inset 0 1px 0 rgba(0, 0, 0, 0.15); --p-override-none:none; --p-override-transparent:transparent; --p-override-one:1; --p-override-visible:visible; --p-override-zero:0; --p-override-loading-z-index:514; --p-button-font-weight:500; --p-non-null-content:''; --p-choice-size:2rem; --p-icon-size:1rem; --p-choice-margin:0.1rem; --p-control-border-width:0.2rem; --p-banner-border-default:inset 0 0.1rem 0 0 var(--p-border-neutral-subdued), inset 0 0 0 0.1rem var(--p-border-neutral-subdued); --p-banner-border-success:inset 0 0.1rem 0 0 var(--p-border-success-subdued), inset 0 0 0 0.1rem var(--p-border-success-subdued); --p-banner-border-highlight:inset 0 0.1rem 0 0 var(--p-border-highlight-subdued), inset 0 0 0 0.1rem var(--p-border-highlight-subdued); --p-banner-border-warning:inset 0 0.1rem 0 0 var(--p-border-warning-subdued), inset 0 0 0 0.1rem var(--p-border-warning-subdued); --p-banner-border-critical:inset 0 0.1rem 0 0 var(--p-border-critical-subdued), inset 0 0 0 0.1rem var(--p-border-critical-subdued); --p-badge-mix-blend-mode:luminosity; --p-thin-border-subdued:0.1rem solid var(--p-border-subdued); --p-text-field-spinner-offset:0.2rem; --p-text-field-focus-ring-offset:-0.4rem; --p-text-field-focus-ring-border-radius:0.7rem; --p-button-group-item-spacing:-0.1rem; --p-duration-1-0-0:100ms; --p-duration-1-5-0:150ms; --p-ease-in:cubic-bezier(0.5, 0.1, 1, 1); --p-ease:cubic-bezier(0.4, 0.22, 0.28, 1); --p-range-slider-thumb-size-base:1.6rem; --p-range-slider-thumb-size-active:2.4rem; --p-range-slider-thumb-scale:1.5; --p-badge-font-weight:400; --p-frame-offset:0px;"
>
    <div x-data="preferencePage">
        <template x-if="!isLoading">
            <div class="Polaris-Page">
                <div class="Polaris-Page__Content">
                    <div class="Polaris-Layout">

                        <div class="Polaris-Layout__Section">
                            <div class="Polaris-Card">
                                <div class="Polaris-CalloutCard__Container">
                                    <div class="Polaris-Card__Section">
                                        <div class="Polaris-CalloutCard">
                                            <div class="Polaris-CalloutCard__Content">
                                                <div class="Polaris-CalloutCard__Title">
                                                    <h2 class="Polaris-Heading">{{ config('app.name') }}</h2>
                                                </div>
                                                <div class="Polaris-TextContainer">
                                                    <p> v{{ config('version.number') }}</p>
                                                </div>

                                            </div>

                                            <img
                                                src="/msp-logo-tagline-color.svg"
                                                alt="" width="200">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="Polaris-Layout__AnnotatedSection">
                            <div x-show="!paymentMethod" class="Polaris-Banner Polaris-Banner--statusCritical Polaris-Banner--withinPage" tabindex="0" role="alert" aria-live="polite" aria-labelledby="PolarisBanner1Heading" aria-describedby="PolarisBanner1Content">
                                <div class="Polaris-Banner__Ribbon">
                                    <span class="Polaris-Icon Polaris-Icon--colorCritical Polaris-Icon--applyColor">
                                      <span class="Polaris-VisuallyHidden">
                                      </span>
                                      <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                        <path d="M11.768.768a2.5 2.5 0 0 0-3.536 0l-7.464 7.464a2.5 2.5 0 0 0 0 3.536l7.464 7.464a2.5 2.5 0 0 0 3.536 0l7.464-7.464a2.5 2.5 0 0 0 0-3.536l-7.464-7.464zm-2.768 5.232a1 1 0 1 1 2 0v4a1 1 0 1 1-2 0v-4zm2 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z">
                                        </path>
                                      </svg>
                                    </span>
                                </div>
                                <div class="Polaris-Banner__ContentWrapper">
                                    <div class="Polaris-Banner__Heading" id="PolarisBanner1Heading">
                                        <p class="Polaris-Heading">Payment method not enabled</p>
                                    </div>
                                    <div class="Polaris-Banner__Content" id="PolarisBanner1Content">
                                        <p>To use this payment method, first you need to enable it in your MultiSafepay dashboard. Once you have enabled the method, click <b>Retry</b>.</p>
                                        <div class="Polaris-Banner__Actions">
                                            <div class="Polaris-ButtonGroup">
                                                <div class="Polaris-ButtonGroup__Item">
                                                    <div class="Polaris-Banner__PrimaryAction">
                                                        <button class="Polaris-Banner__Button" type="button" onclick="location.reload()">Retry</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="paymentMethod" class="Polaris-Layout__AnnotationWrapper">
                                <div class="Polaris-Layout__Annotation">
                                    <div class="Polaris-TextContainer">
                                        <h2 class="Polaris-Heading">Settings</h2>
                                        <p>The settings can be obtained from your MultiSafepay account. For
                                            more info please visit our <a
                                                href="https://docs.multisafepay.com/account/site-id-api-key-secure-code/"
                                                target="_blank"
                                                class="Polaris-Link" data-polaris-unstyled="true">Docs</a>.</p>
                                    </div>
                                </div>
                                <div class="Polaris-Layout__AnnotationContent">
                                    <div class="Polaris-Card">
                                        <div class="Polaris-Card__Section">
                                            <div class="Polaris-FormLayout">
                                                <div x-show="apiKey">
                                                    <div>
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="api-key"
                                                                               class="Polaris-Label__Text">
                                                                            Website API Key
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-TextField">
                                                                    <input
                                                                        type="text"
                                                                        name="api-key"
                                                                        id="api-key"
                                                                        x-model="apiKey"
                                                                        class="Polaris-TextField__Input"
                                                                    />
                                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="environment"
                                                                               class="Polaris-Label__Text">
                                                                            Environment
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Select">
                                                                    <select name="environment" id="environment"
                                                                            x-model="environment"
                                                                            class="Polaris-Select__Input"
                                                                            aria-invalid="false">
                                                                        <template x-for="env in possibleEnvironment">
                                                                            <option :key="env" :value="env" x-text="env"
                                                                                    :selected="env===environment"></option>
                                                                        </template>
                                                                    </select>
                                                                    <div class="Polaris-Select__Content" aria-hidden="true">
                                                                    <span class="Polaris-Select__SelectedOption"
                                                                          id="environment-select-content"
                                                                          x-text="environment">Test</span>
                                                                        <span class="Polaris-Select__Icon">
                                                                        <span class="Polaris-Icon">
                                                                            <svg viewBox="0 0 20 20"
                                                                                 class="Polaris-Icon__Svg"
                                                                                 focusable="false"
                                                                                 aria-hidden="true">
                                                                                <path
                                                                                    d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                    </span>
                                                                    </div>
                                                                    <div class="Polaris-Select__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div x-show="!apiKey">
                                                    <div x-show="isNew">
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="api-key"
                                                                               class="Polaris-Label__Text">
                                                                            Website API Key
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-TextField">
                                                                    <input
                                                                        type="text"
                                                                        name="api-key"
                                                                        id="api-key"
                                                                        x-model="apiKey"
                                                                        class="Polaris-TextField__Input"
                                                                    />
                                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="Polaris-FormLayout__Item">
                                                            <div class="">
                                                                <div class="Polaris-Labelled__LabelWrapper">
                                                                    <div class="Polaris-Label">
                                                                        <label for="environment"
                                                                               class="Polaris-Label__Text">
                                                                            Environment
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="Polaris-Select">
                                                                    <select name="environment" id="environment"
                                                                            x-model="environment"
                                                                            class="Polaris-Select__Input"
                                                                            aria-invalid="false">
                                                                        <template x-for="env in possibleEnvironment">
                                                                            <option :key="env" :value="env" x-text="env"
                                                                                    :selected="env===environment"></option>
                                                                        </template>
                                                                    </select>
                                                                    <div class="Polaris-Select__Content"
                                                                         aria-hidden="true">
                                                                    <span class="Polaris-Select__SelectedOption"
                                                                          id="environment-select-content"
                                                                          x-text="environment">Test</span>
                                                                        <span class="Polaris-Select__Icon">
                                                                        <span class="Polaris-Icon">
                                                                            <svg viewBox="0 0 20 20"
                                                                                 class="Polaris-Icon__Svg"
                                                                                 focusable="false"
                                                                                 aria-hidden="true">
                                                                                <path
                                                                                    d="m10 16-4-4h8l-4 4zm0-12 4 4H6l4-4z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                    </span>
                                                                    </div>
                                                                    <div class="Polaris-Select__Backdrop"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Polaris-FormLayout__Item">
                                                    <button x-show="!isSaving"
                                                            x-on:click="save"
                                                            class="Polaris-Button Polaris-Button--primary"
                                                    >
                                                        <span class="Polaris-Button__Content"><span>Save
                                                                    <span
                                                                        x-show="isNew"> and continue </span> </span></span>
                                                    </button>
                                                    <button x-show="isSaving"
                                                            class="Polaris-Button Polaris-Button--primary"
                                                            disabled>
                                                        <span class="Polaris-Button__Content"><span>Save
                                                                    <span
                                                                        x-show="isNew"> and continue </span> </span></span>
                                                    </button>
                                                    <button x-show="activated"
                                                            x-on:click="reActivateShop"
                                                            class="Polaris-Button Polaris-Button--primary"
                                                            style="float: right"
                                                    >
                                                        <span class="Polaris-Button__Content">
                                                            <span>
                                                                Re-activate
                                                            </span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Polaris-Layout__AnnotatedSection">
                            <div class="Polaris-Layout__AnnotationWrapper">
                                <div class="Polaris-Layout__Annotation">
                                    <div class="Polaris-TextContainer">
                                        <h2 class="Polaris-Heading">Info</h2>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="Polaris-Layout__AnnotationContent">
                                    <div class="Polaris-Card">
                                        <div class="Polaris-Card__Section">
                                            <div class="Card__Section">
                                                <h2 class="Polaris-Heading">App</h2>
                                                <p>
                                                    If you have installed a gateway app but the gateway does not display in your checkout, to ensure the gateway is successfully activated, click <b>Reactivate</b>.
                                                </p>
                                                <br>
                                                <p><b>Note:</b> You do <b>not</b> need to repeat the configuration.</p>
                                                <br>
                                                <div x-show="isNew">
                                                    <h2 class="Polaris-Heading">Account</h2>
                                                    <p>
                                                        To use this MultiSafepay app for Shopify, you will need a MultiSafepay account.
                                                        Consider starting with a test account while you explore our offering.
                                                        To apply for a live account, contact Sales:
                                                    </p>
                                                    <br>
                                                    <ul class="Polaris-List">
                                                        <li class="Polaris-List__Item">
                                                            Telephone: <a
                                                                class="Polaris-Link"
                                                                data-polaris-unstyled="true"
                                                                class="text-blue-600"
                                                                href="tel:+31208500501">+31
                                                                (0)20 -
                                                                8500501</a></li>
                                                        <li class="Polaris-List__Item">
                                                            E-mail: <a class="Polaris-Link"
                                                                       data-polaris-unstyled="true"
                                                                       class="text-blue-600"
                                                                       href="mailto:sales@multisafepay.com">sales@multisafepay.com</a>
                                                        </li>
                                                    </ul>
                                                    <br>
                                                </div>
                                                <h2 class="Polaris-Heading">Support</h2>
                                                <p> Contact the Integration Team:</p>
                                                <br>
                                                <ul class="Polaris-List">
                                                    <li class="Polaris-List__Item">
                                                        E-mail: <a class="Polaris-Link"
                                                                   data-polaris-unstyled="true"
                                                                   href="mailto:integration@multisafepay.com">integration@multisafepay.com</a>
                                                    </li>
                                                    <li class="Polaris-List__Item">
                                                        Telephone: <a class="Polaris-Link"
                                                                      data-polaris-unstyled="true"
                                                                      href="tel:+31208500500">+31 (0)20 - 8500500</a></li>
                                                </ul>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="Polaris-Layout__Section">
                            <div class="Polaris-FooterHelp">
                                <div class="Polaris-FooterHelp__Content">
                                    <div class="Polaris-FooterHelp__Text">
                                        For more info please visit our
                                        <a
                                            class="Polaris-Link"
                                            data-polaris-unstyled="true"
                                            href="https://docs.multisafepay.com/"
                                            target="_blank">
                                            Docs</a>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>


<script>

    /*params*/
    const searchParams = new URLSearchParams(window.location.search);
    const host = searchParams.get('host');
    const shop = searchParams.get('shop');
    const gateway = searchParams.get('gateway');
    const clientKey = searchParams.get('apiKey');

    /*imports and utils*/
    const AppBridge = window['app-bridge'];
    const AppUtils = window['app-bridge-utils'];
    const createApp = AppBridge.default;
    const getSessionToken = AppUtils.getSessionToken
    const Actions = AppBridge.actions;
    const Toast = Actions.Toast;

    let app = createApp({
        apiKey: clientKey,
        host: host
    });

    const loading = Actions.Loading.create(app);
    loading.dispatch(Actions.Loading.Action.START);

    const redirect = Actions.Redirect.create(app);

    const getShopInfo = async token => {
        return await fetch(`{{route('shopify.preference.get')}}?shop=${shop}&gateway=${gateway}`, {
            method: 'GET',
            headers: new Headers({
                'Authorization': 'Bearer ' + token,
            })
        });
    };

    const storeShopInfo = async (token, data) => {
        return await fetch(`{{route('shopify.preference.get')}}?shop=${shop}`, {
            method: 'POST',
            headers: new Headers({
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*',
                'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].getAttribute('content'),
            }),
            body: JSON.stringify(data)
        });
    };
    const activateShopGateway = async (token, data) => {
        return await fetch(`{{route('shopify.preference.activate')}}?shop=${shop}`, {
            method: 'POST',
            headers: new Headers({
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*',
                'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].getAttribute('content'),
            }),
            body: JSON.stringify(data)
        });
    };

    function wait(ms) {
        var start = new Date().getTime();
        var end = start;
        while (end < start + ms) {
            end = new Date().getTime();
        }
    }

    let preferencePage = () => {
        return {
            paymentMethod: false,
            reActivate: false,
            isSaving: false,
            isLoading: true,
            isNew: true,
            sessionToken: null,
            apiKey: '',
            activated: false,
            environment: '',
            possibleEnvironment: ['test', 'live'],
            async init() {
                await this.get();
                await this.activateShop();

                this.isLoading = false;
                loading.dispatch(Actions.Loading.Action.STOP);
            },
            async refreshToken() {
                this.sessionToken = await getSessionToken(app);
            },
            async get() {
                await this.refreshToken();
                let response = await getShopInfo(this.sessionToken);

                if (response.status === 200) {
                    let data = await response.json();
                    this.apiKey = data['apiKey'];
                    this.environment = data['environment'] ?? 'test';
                    this.activated = data['activated'];
                    this.paymentMethod = data['enabledGateway'];
                } else {
                    /* show error */
                    const toastNotice = Toast.create(app, {
                        message: 'An error occurred. Please reload the page and try again.',
                        duration: 5000,
                    });
                    toastNotice.dispatch(Toast.Action.SHOW);
                }
            },
            async reActivateShop()
            {
                this.reActivate = true;
                await this.activateShop();
            },
            async activateShop() {
                if (this.activated && !this.reActivate || !this.apiKey && !this.reActivate) {
                    return;
                }
                this.reActivate = false;

                this.isSaving = true;
                /* show automatic activation */
                const toastNotice = Toast.create(app, {
                    message: 'Activating payment gateway',
                    duration: 3000,
                });

                toastNotice.dispatch(Toast.Action.SHOW);
                let data = {
                    'apiKey': this.apiKey,
                    'gateway': gateway,
                    'clientKey': clientKey
                }

                let response = await activateShopGateway(this.sessionToken, data);

                if (response.status === 200) {
                    let json = await response.json();
                    if (json['type']) {
                        if (json['type'] === 'redirect') {
                            redirect.dispatch(Actions.Redirect.Action.REMOTE, {
                                url: json['url'],
                                newContext: true,
                            });
                        } else if (json['type'] === 'error') {
                            const toastNotice = Toast.create(app, {
                                message: 'Error: ' + json['error'].join(', '),
                                duration: 7500,
                            });
                            toastNotice.dispatch(Toast.Action.SHOW);
                        }
                    } else {
                        const toastNotice = Toast.create(app, {
                            message: 'Saved',
                            duration: 7500,
                        });
                        toastNotice.dispatch(Toast.Action.SHOW);
                    }
                } else {
                    /* show error */
                    const toastNotice = Toast.create(app, {
                        message: 'An error occurred. Please reload the page and try again.',
                        duration: 7500,
                    });
                    toastNotice.dispatch(Toast.Action.SHOW);
                }
                this.isSaving = false;
            },
            async save() {
                this.isSaving = true;
                loading.dispatch(Actions.Loading.Action.START);
                await this.refreshToken();

                let data = {
                    'apiKey': this.apiKey,
                    'environment': this.environment,
                    'gateway': gateway,
                    'clientKey': clientKey
                }

                let response = await storeShopInfo(this.sessionToken, data);

                if (response.status === 200) {
                    let json = await response.json();
                    if (json['type']) {
                        if (json['type'] === 'redirect') {
                            const toastNotice = Toast.create(app, {
                                message: 'Api key saved, redirecting user',
                                duration: 7500,
                            });
                            toastNotice.dispatch(Toast.Action.SHOW);
                            wait(2500);
                            redirect.dispatch(Actions.Redirect.Action.REMOTE, {
                                url: json['url'],
                                newContext: true,
                            });
                        } else if (json['type'] === 'error') {
                            const toastNotice = Toast.create(app, {
                                message: 'Error: ' + json['error'].join(', '),
                                duration: 7500,
                            });
                            toastNotice.dispatch(Toast.Action.SHOW);
                        } else if (json['type'] === 'gatewayError'){
                            this.paymentMethod = false;
                        }
                    } else {
                        const toastNotice = Toast.create(app, {
                            message: 'Saved',
                            duration: 7500,
                        });
                        toastNotice.dispatch(Toast.Action.SHOW);
                    }
                } else {
                    /* show error */
                    const toastNotice = Toast.create(app, {
                        message: 'An error occurred. Please reload the page and try again.',
                        duration: 7500,
                    });
                    toastNotice.dispatch(Toast.Action.SHOW);
                }

                loading.dispatch(Actions.Loading.Action.STOP);

                this.isSaving = false;
            }
        };
    };

</script>
</body>
</html>
