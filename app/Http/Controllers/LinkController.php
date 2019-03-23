<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use JonnyW\PhantomJs\Exception\InvalidExecutableException;
use JonnyW\PhantomJs\Http\ResponseInterface;
use Josh\Component\PhantomJs\PhantomJs;

class LinkController extends Controller
{
    /**
     * Check all active links
     *
     * @return Redirect
     */
    public function checkLinks()
    {
        if(!auth()->user()->isAdmin()) abort(404);
        set_time_limit(0);
        foreach (Link::getActive()->get() as $link) {
            $this->checkOneLink($link);
        }
        return redirect('/admin/resources/links');
    }

    /**
     * Check link
     * @param Link $link
     * @return Redirect
     */
    public function checkLink(Link $link)
    {
        if(!auth()->user()->isAdmin()) abort(404);
        set_time_limit(0);
        $this->checkOneLink($link);
        return redirect('/admin/resources/links/'.$link->id);
    }

    /**
     * Check one link
     *
     * @param Link $link
     * @return void
     */
    public function checkOneLink(Link $link)
    {
        $response = $this->getPage($link->checked_url);
        $status = $response->getStatus();
        if ($status === 200) {
            try{
                $test = strpos($response->getContent(), $link->presence_url.'"');
                if ($test === false) $this->failedStrPos($link, $response);
                else $link->updateAfterCheck(['c_code' => $status, 'p_code' => 200]);
            } catch (\Exception $e)
            {
                $link->updateAfterCheck(['c_code' => $status, 'p_code' => 404]);
            }

        } else $link->updateAfterCheck(['c_code' => $status ? $status : 404, 'p_code' => $status ? $status : 404]);
    }

    /**
     * If there is no presence url on checked url
     *
     * @param Link $link
     * @param mixed $response
     * @return void
     */
    private function failedStrPos(Link $link, $response)
    {
        // Get all substrings from the url
        $preparedLink = $this->prepareSeparateLink($link->presence_url);

        // Check all substrings for existing on the page
        $status = 200;
        if(strpos($response->getContent(), $preparedLink) === false) $status = 404;

        // Update the link info
        $link->updateAfterCheck(['c_code' => $response->status, 'p_code' => $status]);
    }

    /**
     * Replace symbols
     *
     * @param string $str
     * @return string
     */
    private function prepareSeparateLink($str)
    {
        $link = str_replace("://", "%3A%2F%2F", $str);
        $link = str_replace("/", "%2F", $link);
        return $link;
    }

    /**
     * Get html from url using PhantomJs
     *
     * @param string $link
     * @return ResponseInterface
     */
    private function getPage($link)
    {
        $phantom = new PhantomJs();
        $phantom->setClient();
        $phantom->getEngine()->setPath(base_path().'/bin/lin-64-phantomjs');
        $request = $phantom->get($link);
        $response = $phantom->send($request);
        return $response;
    }

    /**
     * Get the page CRUD Link
     *
     * @return View
     */
    public function index()
    {
        $links = Link::getForCurrentUser()->orderBy('presence_url_added', 'desc')->paginate(5);
        return view('app.links', compact('links'));
    }

    /**
     * Delete the Link
     *
     * @param Link $link
     * @return string
     *
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function delete(Link $link)
    {
        $this->authorize('userAccess', $link);
        $link->delete();
        return json_encode(['status' => 'success', 'message' => 'The link was successfully deleted.']);
    }

    /**
     * Update the Link
     *
     * @param Link $link
     * @param Request $request
     * @return string
     *
     * @throws AuthorizationException
     */
    public function update(Link $link, Request $request)
    {
        $this->authorize('userAccess', $link);
        if($request->has('active')) $request['active'] = $request['active'] == 'true' ? 1 : 0;
        $link->update($request->only([ 'email', 'details', 'active', 'checked_url', 'presence_url']));
        return json_encode(['status' => 'success', 'message' => 'The link was successfully updated.']);
    }

    /**
     * Create the Link
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        $link = Link::create($request->only([ 'email', 'details', 'active', 'checked_url', 'presence_url', 'user_id']));
        return json_encode(['status' => 'success', 'message' => 'The link was successfully created.', 'id' => $link->id, 'presence_url_added' => strval (Link::find($link->id)->presence_url_added->format('F d, Y h:i A')) ]) ;
    }

    /**
     * Get links with pagination
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        if($request->ajax()) return Link::getForCurrentUser()->orderBy('presence_url_added', 'desc')->paginate(5);
        return abort(404);
    }

    /**
     * Set checkout time
     *
     * @param Request $request
     * @return string
     */
    public function setCheckoutTime(Request $request)
    {
        if(!auth()->user()->isAdmin()) abort(404);
        File::put('time', $request->checkout_time);
        return json_encode(['status' => 'success', 'message' => 'The time was successfully updated.', 'time' => File::get('time')]);
    }

    /**
     * Get checkout time
     *
     * @return string
     */
    public function getCheckoutTime()
    {
        if(!auth()->user()->isAdmin() || !request()->ajax()) abort(404);
        return File::get('time');
    }

}
