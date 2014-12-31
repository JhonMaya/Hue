<?php exit() ?>--by klokje 80.57.15.158
function _G.ProLoad(object, one, two)
	local ipairs = ipairs
	local type = type
	local char = string.char
	local byte = string.byte
	local concat = table.concat

	local t = {}
	for i=1,#object do
		if type(object[i]) == "table" then
			for j = 1, object[i][2], 1 do
		    	t[#t + 1] = char(object[i][1])
		    end
		else 
			t[#t + 1] = char(object[i])
		end
	end
	local t = concat(t)
	local on = {}
	for i=1,#one do
		on[one[i]] = one[i]
	end
	local tw = {}
	for i=1,#two do
		tw[two[i]] = two[i]
	end

	local function dec(str, one, two)
		local one = one
		local two = two 
		local str = str
	  local k = {}
	  local goed = {}
	  local temp = {}
	  local right = byte(str,#str,#str)
	  k[#k + 1] = char(right)
	  if one[1] then right = right*-1 
	     temp[1] = 1 end  
	  for i = 1, #str - 2, 1 do 
	    sec = byte(str,i,i)
	    if two[i] then sec = sec *-1 end
	    
	    right = right + sec
	    rig = right
	    if right < 0 then rig = right*-1 temp[i+1] = i+1 end
	    
	    k[#k + 1] =  char(rig)
	  end
	  k = concat(k)
	  right = byte(str,#str-1,#str-1)
	  goed[#goed + 1] = char(right)

	  for j= 1, #k, 1 do  
	    sec = byte(k,j,j)
	    if temp[j] then sec = sec *-1 end

	    right = right + sec
	    goed[#goed + 1] = char(right)
	    
	  end
	  return concat(goed)
	end
	local function Test()
	  if debug.getinfo(string.dump, _A_'$').what ~= _A_'r' or debug.getinfo(xpcall, _A_'$').what ~= _A_'r' or debug.getinfo(string.find, _A_'$').what ~= _A_'r' then return false end 
	  local yy = 0

	  q, p = xpcall(function()  return string.dump(debug.getinfo) end, function(msg) if string.find(msg, _A_'F?23=6 E@ 5F>A 8:G6? 7F?4E:@?') and not string.find(msg, _A_'i') then  yy = yy + 1 end  end)
	  local h,g = xpcall(function() return string.dump(xpcall) end, function(msg) if string.find(msg, _A_'F?23=6 E@ 5F>A 8:G6? 7F?4E:@?') and not string.find(msg, _A_'i') then yy = yy + 1 end  end)
	  
	  if not xpcall(function() return string.dump(Test) end, function(msg) end) or q or yy < 2 then return false end 

	  if debug.getinfo(_G.string.dump, _A_'$').what == _A_'{F2' then return false end
	  if debug.getinfo(debug.getinfo, _A_'F').what then return false end 
	  if debug.getinfo(debug.getinfo, _A_'$').what == _A_'r' then
	      local kk = debug.getinfo
	    _G.debug.getinfo = function(func, what)
	      local info = kk(func, what)
	      return info
	    end 
	    if debug.getinfo(debug.getinfo, _A_'$').what == _A_'r' then return false end
	    _G.debug.getinfo = kk
	    if kk(kk, _A_'$').what == _A_'{F2' then return false end
	    local fff = debug.getinfo(_G.string.dump, _A_'$').what
	    local menno =  _G.string.dump
	    _G.string.dump = function(info)
	      return menno(info)
	    end
	    if fff == debug.getinfo(_G.string.dump, _A_'$').what then return false end
	    _G.string.dump = menno

	    local n, v = debug.getupvalue(GetUser, 1)
	    local n1, v1 = debug.getupvalue(GetUser, 2)
	    if debug.getinfo(GetUser, "u").nups ~= 2 or not v == "userdata" or not v1 == "userdata" then
	    	return false
	    end

	    return true
	  end
	  return false
	end

	local function _A(a) local _a_,_a = string.byte, string.char local A_ = _a_(a) if A_ >= _a_('!') and A_ <= _a_('O') then A_ = ((A_ + 47) % 127) elseif A_ >= _a_('P') and A_ <= _a_('~') then A_ = ((A_ - 47) % 127) end return _a(A_) end function _A_(a) local _a_ = "" for n=1, a:len() do _a_ = _a_.._A(a:sub(n,n)) end return _a_ end __A = print
	local function check(func) if debug.getinfo(func, 'S').what == "C" then return true else return false end end

	if not Test() or not check(load) or not check(GetAsyncWebResult)  or not check(Base64Decode) or not check(string.char) or not check(string.byte) or not check(string.dump) or not check(string.find) or not check(os.getenv) or not check(GetUser) or not check(GetRegion) or not check(GetWebResult) or not check(GetGameTimer) then 	
		return false
	end
	
	local yes1 = dec(t, on, tw)
	local yes2 = Base64Decode(yes1)
	yes1 = nil
	local zz = load(yes2)
	yes2 = nil
	zz()
	zz = nil
	yes2 = nil
	t = nil
	on = nil
	tw = nil
	object = nil
	one = nil
	two = nil
	collectgarbage()
end

function _G.ImLoading(object, one, two)
	ProLoad(object, one, two)
end

_G.PING_ALERT = 176
_G.PING_DANGER = 178
_G.PING_ENEMY_MISSING = 179
_G.PING_ON_MY_WAY = 180
_G.PING_RETREAT = 181
_G.PING_ASSIST_ME = 182

_ENV = AdvancedCallback:register('OnPing')

function OnRecvPacket(p)
	if p.header == 0x3F then 
		p.pos = 5
		local x = p:DecodeF()
		local z = p:DecodeF()
		local targetNetworkID = p:DecodeF()
		local sourceNetworkID = p:DecodeF()
		local typeId = p:Decode1()
		local unit = objManager:GetObjectByNetworkId(sourceNetworkID)
		local target = objManager:GetObjectByNetworkId(targetNetworkID)

		if unit and unit.valid then
			if AdvancedCallback:OnPing(unit, {type = typeId, pos = Vector(x, myHero.y, z), target = target, }) == false then
                p:Block()
            end
		end 
	end
end