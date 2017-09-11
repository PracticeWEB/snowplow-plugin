
window.snowplow('newTracker', 'cf',tracker_data.collector, {"appId":tracker_data.application_id, "platform":"web", "cookieDomain":tracker_data.cookie_domain, "contexts":{"webPage":true, "gaCookies":true, "performanceTiming":true}, "post":true});
window.snowplow('enableActivityTracking', 30, 30);
window.snowplow('enableLinkClickTracking');
window.snowplow('trackPageView');