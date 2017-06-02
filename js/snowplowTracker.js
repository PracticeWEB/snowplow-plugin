
window.snowplow('newTracker', 'cf',sp_data.collector, {"appId":sp_data.application_id, "platform":"web", "cookieDomain":sp_data.cookie_domain, "contexts":{"webPage":true, "gaCookies":true, "performanceTiming":true}, "post":true});
window.snowplow('enableActivityTracking', 30, 30);
window.snowplow('enableLinkClickTracking');
window.snowplow('trackPageView');