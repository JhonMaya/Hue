<?php exit() ?>--by iRes 92.30.14.208
local _VERSION = "v1.01.12"
local _NAME = "Hit Box Prodiction"
local _AUTHOR = "RiotSpy"

local _PrintChat = PrintChat
local function PrintChat(message)
	_PrintChat("[" .. _NAME .. "] " .. message)
end

function _G.GetHitBoxProdictVersion()
	return _VERSION
end

function _G.PrintHitBoxProdictInfo()
	PrintChat("You are using: " .. _NAME)
	PrintChat("By: " .. _AUTHOR)
	PrintChat("You are running version: " .. _VERSION)
end

function GetHitBoxRadius(target, d)
	d = d or 0
	if ValidTarget(target) then
		return (target.collisionRadius + d)/2
	end
	return 0
end

local function pnt(vec)
	local vx = WorldToScreen(D3DXVECTOR3(vec.x, myHero.y, vec.z))
	
	return Point(vx.x, vx.y)
end

local function extend(v,amt,f)
	f = f or myHero
	
	return Vector(v) + (Vector(v) - Vector(f)):normalized() * (amt)
end

local function extend2(v,amt,f)
	f = f or myHero
	
	return Vector(v) + (Vector(v) - Vector(f)):perpendicular():normalized() * (amt)	
end

function GenColMap(a,b,c,d, minions, prediction)
	local map = {}
	for indx, minion in pairs(minions) do
		local mhbr = GetHitBoxRadius(minion, d)
		local mpos = prediction:GetPrediction(minion)
		if mpos ~= nil then
			local mp1 = extend(extend2(mpos,mhbr),-mhbr)
			local mp2 = extend(extend2(mpos,-mhbr),-mhbr)
			local mp3 = extend(mp1, 2000)
			local mp4 = extend(mp2, 2000)
			local poly = Polygon(pnt(mp3),pnt(mp1), pnt(mp2),pnt(mp4))
			table.insert(map, poly)
		end
	end
	
	return map
end

function IsInColMap(map, p)
	for i = 1, #map do
		local v = map[i]
		
		if v:contains(pnt(p)) then
			return true
		end
	end
	
	return false
end

function DrawPoly(map)
	for i = 1, #map do
		local v = map[i]:getPoints()
		local prev = nil
		for n, b in pairs(v) do
			if prev ~= nil then
				DrawLine(prev.x, prev.y,b.x, b.y, 2, 2684354716)
			end
			
			prev = b
		end
	end
	
	if IsInColMap(map, mousePos) then 
		DrawCircle(mousePos.x, mousePos.y, mousePos.z, 50, 0x7F006E)
	end
end

function AnyProdictHitBox(pos,collision,target, a, b, c, d)
	if pos == nil then return nil end
	
	local hbr = GetHitBoxRadius(target, d)
	
	local p1 = extend(extend2(pos,hbr),-hbr+d)
	local p2 = extend(extend2(pos,-hbr),-hbr+d)
	
	function GetNextSpot(target, pos, i)
		local sm = i % 4
		local d1 = (Vector(p2 - p1):normalized() * i - sm)/2
		
		if sm >= 2 then
			return p1 + d1
		else
			return p2 - d1
		end
	end
	
	function GetHitBoxIteration(target, i)
		i = i or math.floor(hbr)
		
		local s1 = GetNextSpot(target, pos, i)
		local wc1 = IsInColMap(collision, s1)
		
		if GetDistance(s1) < a and not wc1 then
			return s1
		end
		
		if i >= 2 then return GetHitBoxIteration(target, i-2) else return nil end
	end
	
	return GetHitBoxIteration(target)
end
