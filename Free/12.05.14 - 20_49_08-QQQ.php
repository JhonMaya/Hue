<?php exit() ?>--by QQQ 84.160.234.153
if not VIP_USER then
	rawset(_G, "LoadVIPScript", function() return end)
end

local function _TS_Draw_Init()
    if not _TS_Draw then
        UpdateWindow()
        _TS_Draw = { y1 = 0, height = 0, fontSize = WINDOW_H and math.round(WINDOW_H / 54) or 14, width = WINDOW_W and math.round(WINDOW_W / 4.8) or 213, border = 2, background = 1413167931, textColor = 4290427578, redColor = 1422721024, greenColor = 1409321728, blueColor = 2684354716 }
        _TS_Draw.cellSize, _TS_Draw.midSize, _TS_Draw.row1, _TS_Draw.row2, _TS_Draw.row3, _TS_Draw.row4 = _TS_Draw.fontSize + _TS_Draw.border, _TS_Draw.fontSize / 2, _TS_Draw.width * 0.6, _TS_Draw.width * 0.7, _TS_Draw.width * 0.8, _TS_Draw.width * 0.9
    end
end

local function TS__DrawMenu(x, y, enemyTeam)
    assert(type(x) == "number" and type(y) == "number", "TS__DrawMenu: wrong argument types (<number>, <number> expected)")
    _TS_Draw_Init()
    local enemyTeam = (enemyTeam ~= false)
    local y1 = y
    for _, _gameHero in ipairs(GetEnemyHeroes()) do
        if _gameHero.enemy == enemyTeam then
            DrawLine(x - _TS_Draw.border, y1 + _TS_Draw.midSize, x + _TS_Draw.row1 - _TS_Draw.border, y1 + _TS_Draw.midSize, _TS_Draw.cellSize, (_gameHero.ignore and _TS_Draw.redColor or _TS_Draw.background))
            DrawText(_gameHero.hero.charName, _TS_Draw.fontSize, x, y1, _TS_Draw.textColor)
            DrawLine(x + _TS_Draw.row1, y1 + _TS_Draw.midSize, x + _TS_Draw.row2 - _TS_Draw.border, y1 + _TS_Draw.midSize, _TS_Draw.cellSize, _TS_Draw.background)
            DrawText("   " .. (_gameHero.ignore and "-" or tostring(_gameHero.priority)), _TS_Draw.fontSize, x + _TS_Draw.row1, y1, _TS_Draw.textColor)
            DrawLine(x + _TS_Draw.row2, y1 + _TS_Draw.midSize, x + _TS_Draw.row3 - _TS_Draw.border, y1 + _TS_Draw.midSize, _TS_Draw.cellSize, _TS_Draw.blueColor)
            DrawText("   -", _TS_Draw.fontSize, x + _TS_Draw.row2, y1, _TS_Draw.textColor)
            DrawLine(x + _TS_Draw.row3, y1 + _TS_Draw.midSize, x + _TS_Draw.row4 - _TS_Draw.border, y1 + _TS_Draw.midSize, _TS_Draw.cellSize, _TS_Draw.blueColor)
            DrawText("   +", _TS_Draw.fontSize, x + _TS_Draw.row3, y1, _TS_Draw.textColor)
            DrawLine(x + _TS_Draw.row4, y1 + _TS_Draw.midSize, x + _TS_Draw.width, y1 + _TS_Draw.midSize, _TS_Draw.cellSize, _TS_Draw.redColor)
            DrawText("   X", _TS_Draw.fontSize, x + _TS_Draw.row4, y1, _TS_Draw.textColor)
            y1 = y1 + _TS_Draw.cellSize
        end
    end
    return y1
end

SCRIPT_PARAM_ONOFF = 1
SCRIPT_PARAM_ONKEYDOWN = 2
SCRIPT_PARAM_ONKEYTOGGLE = 3
SCRIPT_PARAM_SLICE = 4
SCRIPT_PARAM_INFO = 5
SCRIPT_PARAM_COLOR = 6
SCRIPT_PARAM_LIST = 7
local _SC = { init = true, initDraw = true, menuKey = 16, useTS = false, menuIndex = -1, instances = {}, _changeKey = false, _changeKeyInstance = false, _sliceInstance = false, _listInstance = false }
class'scriptConfig'
local function __SC__remove(name)
    if not GetSave("scriptConfig")[name] then GetSave("scriptConfig")[name] = {} end
    table.clear(GetSave("scriptConfig")[name])
end

local function __SC__load(name)
    if not GetSave("scriptConfig")[name] then GetSave("scriptConfig")[name] = {} end
    return GetSave("scriptConfig")[name]
end

local function __SC__save(name, content)
    if not GetSave("scriptConfig")[name] then GetSave("scriptConfig")[name] = {} end
    table.clear(GetSave("scriptConfig")[name])
    table.merge(GetSave("scriptConfig")[name], content, true)
end

local function __SC__saveMaster()
    local config = {}
    local P, PS, I = 0, 0, 0
    for _, instance in pairs(_SC.instances) do
        I = I + 1
        P = P + #instance._param
        PS = PS + #instance._permaShow
    end
    _SC.master["I" .. _SC.masterIndex] = I
    _SC.master["P" .. _SC.masterIndex] = P
    _SC.master["PS" .. _SC.masterIndex] = PS
    if not _SC.master.useTS and _SC.useTS then _SC.master.useTS = true end
    for var, value in pairs(_SC.master) do
        config[var] = value
    end
    __SC__save("Master", config)
end

local function __SC__updateMaster()
    _SC.master = __SC__load("Master")
    _SC.masterY, _SC.masterYp = 1, 0
    _SC.masterY = (_SC.master.useTS and 1 or 0)
    for i = 1, _SC.masterIndex - 1 do
        _SC.masterY = _SC.masterY + _SC.master["I" .. i]
        _SC.masterYp = _SC.masterYp + _SC.master["PS" .. i]
    end
    local size, sizep = (_SC.master.useTS and 2 or 1), 0
    for i = 1, _SC.master.iCount do
        size = size + _SC.master["I" .. i]
        sizep = sizep + _SC.master["PS" .. i]
    end
    _SC.draw.height = size * _SC.draw.cellSize
    _SC.pDraw.height = sizep * _SC.pDraw.cellSize
    _SC.draw.x = _SC.master.x
    _SC.draw.y = _SC.master.y
    _SC.pDraw.x = _SC.master.px
    _SC.pDraw.y = _SC.master.py
    _SC._Idraw.x = _SC.draw.x + _SC.draw.width + _SC.draw.border * 2
end

local function __SC__saveMenu()
    __SC__save("Menu", { menuKey = _SC.menuKey, draw = { x = _SC.draw.x, y = _SC.draw.y }, pDraw = { x = _SC.pDraw.x, y = _SC.pDraw.y } })
    _SC.master.x = _SC.draw.x
    _SC.master.y = _SC.draw.y
    _SC.master.px = _SC.pDraw.x
    _SC.master.py = _SC.pDraw.y
    __SC__saveMaster()
end

local function __SC__init_draw()
    if _SC.initDraw then
        UpdateWindow()
        _SC.draw = { x = WINDOW_W and math.floor(WINDOW_W / 50) or 20, y = WINDOW_H and math.floor(WINDOW_H / 4) or 190, y1 = 0, height = 0, fontSize = WINDOW_H and math.round(WINDOW_H / 54) or 14, width = WINDOW_W and math.round(WINDOW_W / 4.8) or 213, border = 2, background = 1413167931, textColor = 4290427578, trueColor = 1422721024, falseColor = 1409321728, move = false }
        _SC.pDraw = { x = WINDOW_W and math.floor(WINDOW_W * 0.66) or 675, y = WINDOW_H and math.floor(WINDOW_H * 0.8) or 608, y1 = 0, height = 0, fontSize = WINDOW_H and math.round(WINDOW_H / 72) or 10, width = WINDOW_W and math.round(WINDOW_W / 6.4) or 160, border = 1, background = 1413167931, textColor = 4290427578, trueColor = 1422721024, falseColor = 1409321728, move = false }
        local menuConfig = __SC__load("Menu")
        table.merge(_SC, menuConfig, true)
        _SC.color = { lgrey = 1413167931, grey = 4290427578, red = 1422721024, green = 1409321728, ivory = 4294967280 }
        _SC.draw.cellSize, _SC.draw.midSize, _SC.draw.row4, _SC.draw.row3, _SC.draw.row2, _SC.draw.row1 = _SC.draw.fontSize + _SC.draw.border, _SC.draw.fontSize / 2, _SC.draw.width * 0.9, _SC.draw.width * 0.8, _SC.draw.width * 0.7, _SC.draw.width * 0.6
        _SC.pDraw.cellSize, _SC.pDraw.midSize, _SC.pDraw.row = _SC.pDraw.fontSize + _SC.pDraw.border, _SC.pDraw.fontSize / 2, _SC.pDraw.width * 0.7
        _SC._Idraw = { x = _SC.draw.x + _SC.draw.width + _SC.draw.border * 2, y = _SC.draw.y, height = 0 }
        if WINDOW_H < 500 or WINDOW_W < 500 then return true end
        _SC.initDraw = nil
    end
    return _SC.initDraw
end

local function __SC__init(name)
    if name == nil then
        return (_SC.init or __SC__init_draw())
    end
    if _SC.init then
        _SC.init = nil
        __SC__init_draw()
        local gameStart = GetGame()
        _SC.master = __SC__load("Master")
        --[[ SurfaceS: Look into it! When loading the master, it screws up the Menu, when you change at the same time the running scripts.
           if _SC.master.osTime ~= nil and _SC.master.osTime == gameStart.osTime then
              for i = 1, _SC.master.iCount do
                  if _SC.master["name" .. i] == name then _SC.masterIndex = i end
              end
              if _SC.masterIndex == nil then
                  _SC.masterIndex = _SC.master.iCount + 1
                  _SC.master["name" .. _SC.masterIndex] = name
                  _SC.master.iCount = _SC.masterIndex
                  __SC__saveMaster()
             end
        else]]
        __SC__remove("Master")
        _SC.masterIndex = 1
        _SC.master.useTS = false
        _SC.master.x = _SC.draw.x
        _SC.master.y = _SC.draw.y
        _SC.master.px = _SC.pDraw.x
        _SC.master.py = _SC.pDraw.y
        _SC.master.osTime = gameStart.osTime
        _SC.master.name1 = name
        _SC.master.iCount = 1
        __SC__saveMaster()
        --end
    end
    __SC__updateMaster()
end

local function __SC__txtKey(key)
    return (key > 32 and key < 96 and " " .. string.char(key) .. " " or "(" .. tostring(key) .. ")")
end

local function __SC__DrawInstance(header, selected)
    DrawLine(_SC.draw.x + _SC.draw.width / 2, _SC.draw.y1, _SC.draw.x + _SC.draw.width / 2, _SC.draw.y1 + _SC.draw.cellSize, _SC.draw.width + _SC.draw.border * 2, (selected and _SC.color.red or _SC.color.lgrey))
    DrawText(header, _SC.draw.fontSize, _SC.draw.x, _SC.draw.y1, (selected and _SC.color.ivory or _SC.color.grey))
    _SC.draw.y1 = _SC.draw.y1 + _SC.draw.cellSize
end

local function __SC__ResetSubIndexes()
    for i, instance in ipairs(_SC.instances) do
        instance:ResetSubIndexes()
    end
end

local __SC__OnDraw, __SC__OnWndMsg
local function __SC__OnLoad()
    if not __SC__OnDraw then
        function __SC__OnDraw()
            if __SC__init() or Console__IsOpen or GetGame().isOver then return end
            if IsKeyDown(_SC.menuKey) or _SC._changeKey then
                if _SC.draw.move then
                    local cursor = GetCursorPos()
                    _SC.draw.x = cursor.x - _SC.draw.offset.x
                    _SC.draw.y = cursor.y - _SC.draw.offset.y
                    _SC._Idraw.x = _SC.draw.x + _SC.draw.width + _SC.draw.border * 2
                elseif _SC.pDraw.move then
                    local cursor = GetCursorPos()
                    _SC.pDraw.x = cursor.x - _SC.pDraw.offset.x
                    _SC.pDraw.y = cursor.y - _SC.pDraw.offset.y
                end
                if _SC.masterIndex == 1 then
                    DrawLine(_SC.draw.x + _SC.draw.width / 2, _SC.draw.y, _SC.draw.x + _SC.draw.width / 2, _SC.draw.y + _SC.draw.height, _SC.draw.width + _SC.draw.border * 2, 1414812756) -- grey
                    _SC.draw.y1 = _SC.draw.y
                    local menuText = _SC._changeKey and not _SC._changeKeyVar and "press key for Menu" or "Menu"
                    DrawText(menuText, _SC.draw.fontSize, _SC.draw.x, _SC.draw.y1, _SC.color.ivory) -- ivory
                    DrawText(__SC__txtKey(_SC.menuKey), _SC.draw.fontSize, _SC.draw.x + _SC.draw.width * 0.9, _SC.draw.y1, _SC.color.grey)
                end
                _SC.draw.y1 = _SC.draw.y + _SC.draw.cellSize
                if _SC.useTS then
                    __SC__DrawInstance("Target Selector", (_SC.menuIndex == 0))
                    if _SC.menuIndex == 0 then
                        DrawLine(_SC._Idraw.x + _SC.draw.width / 2, _SC.draw.y, _SC._Idraw.x + _SC.draw.width / 2, _SC.draw.y + _SC._Idraw.height, _SC.draw.width + _SC.draw.border * 2, 1414812756) -- grey
                        DrawText("Target Selector", _SC.draw.fontSize, _SC._Idraw.x, _SC.draw.y, _SC.color.ivory)
                        _SC._Idraw.y = TS__DrawMenu(_SC._Idraw.x, _SC.draw.y + _SC.draw.cellSize)
                        _SC._Idraw.height = _SC._Idraw.y - _SC.draw.y
                    end
                end
                _SC.draw.y1 = _SC.draw.y + _SC.draw.cellSize + (_SC.draw.cellSize * _SC.masterY)
                for index, instance in ipairs(_SC.instances) do
                    __SC__DrawInstance(instance.header, (_SC.menuIndex == index))
                    if _SC.menuIndex == index then instance:OnDraw() end
				end
            end
            local y1 = _SC.pDraw.y + (_SC.pDraw.cellSize * _SC.masterYp)
            local function DrawPermaShows(instance)
			local varIndex_count = 0
                if #instance._permaShow > 0 then
                    for _, varIndex in ipairs(instance._permaShow) do
                        local pVar = instance._param[varIndex].var
                        DrawLine(_SC.pDraw.x - _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.x + _SC.pDraw.row - _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.cellSize, _SC.color.lgrey)
                        DrawText(instance._param[varIndex].text, _SC.pDraw.fontSize, _SC.pDraw.x, y1, _SC.color.grey)
                        if instance._param[varIndex].pType == SCRIPT_PARAM_SLICE or instance._param[varIndex].pType == SCRIPT_PARAM_LIST or instance._param[varIndex].pType == SCRIPT_PARAM_INFO then
                            DrawLine(_SC.pDraw.x + _SC.pDraw.row, y1 + _SC.pDraw.midSize, _SC.pDraw.x + _SC.pDraw.width + _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.cellSize, _SC.color.lgrey)
                            if instance._param[varIndex].pType == SCRIPT_PARAM_LIST then
                                local text = tostring(instance._param[varIndex].listTable[instance[pVar]])
                                local maxWidth = (_SC.pDraw.width - _SC.pDraw.row) * 0.8
                                local textWidth = GetTextArea(text, _SC.pDraw.fontSize).x
                                if textWidth > maxWidth then
                                    text = text:sub(1, math.floor(text:len() * maxWidth / textWidth)) .. ".."
                                end
                                DrawText(text, _SC.pDraw.fontSize, _SC.pDraw.x + _SC.pDraw.row, y1, _SC.color.grey)
                            else
                                DrawText(tostring(instance[pVar]), _SC.pDraw.fontSize, _SC.pDraw.x + _SC.pDraw.row + _SC.pDraw.border, y1, _SC.color.grey)
                            end
                        else
                            DrawLine(_SC.pDraw.x + _SC.pDraw.row, y1 + _SC.pDraw.midSize, _SC.pDraw.x + _SC.pDraw.width + _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.cellSize, (instance[pVar] and _SC.color.green or _SC.color.lgrey))
                            DrawText((instance[pVar] and "      ON" or "      OFF"), _SC.pDraw.fontSize, _SC.pDraw.x + _SC.pDraw.row + _SC.pDraw.border, y1, _SC.color.grey)
                        end
                        y1 = y1 + _SC.pDraw.cellSize
						varIndex_count = varIndex_count + 1
                    end
                end



                for _, subInstance in ipairs(instance._subInstances) do
                    DrawPermaShows(subInstance)
                end
            end


            local function DrawCustomPermaShows()
				if _G.SV_PERMASHOW ~= nil and _G.SV_PERMASHOW["Count"] ~= nil and _G.SV_PERMASHOW["Count"] > 0 then
					for i = 1,_G.SV_PERMASHOW["Count"] do
						DrawLine(_SC.pDraw.x - _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.x + _SC.pDraw.row - _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.cellSize, _SC.color.lgrey)
						DrawText(_G.SV_PERMASHOW[(i).."_Name"], _SC.pDraw.fontSize, _SC.pDraw.x, y1, _SC.color.grey)
						DrawLine(_SC.pDraw.x + _SC.pDraw.row, y1 + _SC.pDraw.midSize, _SC.pDraw.x + _SC.pDraw.width + _SC.pDraw.border, y1 + _SC.pDraw.midSize, _SC.pDraw.cellSize, (_G.SV_PERMASHOW[(i).."_Status"] and _SC.color.green or _SC.color.lgrey))
						DrawText((_G.SV_PERMASHOW[(i).."_Status"] and "      ON" or "      OFF"), _SC.pDraw.fontSize, _SC.pDraw.x + _SC.pDraw.row + _SC.pDraw.border, y1, _SC.color.grey)
						y1 = y1 + _SC.pDraw.cellSize
					end
				end
            end


            for _, instance in ipairs(_SC.instances) do
                DrawPermaShows(instance)
            end
			    DrawCustomPermaShows()

        end

        AddDrawCallback(__SC__OnDraw)
    end
    if not __SC__OnWndMsg then
        function __SC__OnWndMsg(msg, key)
            if __SC__init() or Console__IsOpen then return end
            local msg, key = msg, key
            if key == _SC.menuKey and _SC.lastKeyState ~= msg then
                _SC.lastKeyState = msg
                __SC__updateMaster()
            end
            if _SC._changeKey then
                if msg == KEY_DOWN then
                    if _SC._changeKeyMenu then return end
                    _SC._changeKey = false
                    if _SC._changeKeyVar == nil then
                        _SC.menuKey = key
                        if _SC.masterIndex == 1 then __SC__saveMenu() end
                    else
                        _SC._changeKeyInstance._param[_SC._changeKeyVar].key = key
                        _SC._changeKeyInstance:save()
                        --_SC.instances[_SC.menuIndex]._param[_SC._changeKeyVar].key = key
                        --_SC.instances[_SC.menuIndex]:save()
                    end
                    return
                else
                    if _SC._changeKeyMenu and key == _SC.menuKey then _SC._changeKeyMenu = false end
                end
            end
            if msg == WM_LBUTTONDOWN and IsKeyDown(_SC.menuKey) then
                if CursorIsUnder(_SC.draw.x, _SC.draw.y, _SC.draw.width, _SC.draw.height) then
                    _SC.menuIndex = -1
                    __SC__ResetSubIndexes()
                    if CursorIsUnder(_SC.draw.x + _SC.draw.width - _SC.draw.fontSize * 1.5, _SC.draw.y, _SC.draw.fontSize, _SC.draw.cellSize) then
                        _SC._changeKey, _SC._changeKeyVar, _SC._changeKeyMenu = true, nil, true
                        return
                    elseif CursorIsUnder(_SC.draw.x, _SC.draw.y, _SC.draw.width, _SC.draw.cellSize) then
                        _SC.draw.offset = Vector(GetCursorPos()) - _SC.draw
                        _SC.draw.move = true
                        return
                    else
                        if _SC.useTS and CursorIsUnder(_SC.draw.x, _SC.draw.y + _SC.draw.cellSize, _SC.draw.width, _SC.draw.cellSize) then _SC.menuIndex = 0 __SC__ResetSubIndexes() end
                        local y1 = _SC.draw.y + _SC.draw.cellSize + (_SC.draw.cellSize * _SC.masterY)
                        for index, _ in ipairs(_SC.instances) do
							if CursorIsUnder(_SC.draw.x, y1, _SC.draw.width, _SC.draw.cellSize) then _SC.menuIndex = index __SC__ResetSubIndexes() end
							y1 = y1 + _SC.draw.cellSize
                        end
                    end
                elseif CursorIsUnder(_SC.pDraw.x, _SC.pDraw.y, _SC.pDraw.width, _SC.pDraw.height) then
                    _SC.pDraw.offset = Vector(GetCursorPos()) - _SC.pDraw
                    _SC.pDraw.move = true
                elseif _SC.menuIndex == 0 then
                    TS_ClickMenu(_SC._Idraw.x, _SC.draw.y + _SC.draw.cellSize)
                elseif _SC.menuIndex > 0 then
                    local function CheckOnWndMsg(instance)
                        if CursorIsUnder(instance._x, _SC.draw.y, _SC.draw.width, instance._height) then
                            instance:OnWndMsg()
                        elseif instance._subMenuIndex > 0 then
                            CheckOnWndMsg(instance._subInstances[instance._subMenuIndex])
                        end
                    end
                    CheckOnWndMsg(_SC.instances[_SC.menuIndex])
                end
            elseif msg == WM_LBUTTONUP then
                if _SC.draw.move or _SC.pDraw.move then
                    _SC.draw.move = false
                    _SC.pDraw.move = false
                    if _SC.masterIndex == 1 then __SC__saveMenu() end
                    return
                elseif _SC._sliceInstance then
                    _SC._sliceInstance:save()
                    _SC._sliceInstance._slice = false
                    _SC._sliceInstance = false

                    return
                elseif _SC._listInstance then
                    _SC._listInstance:save()
                    _SC._listInstance._list = false
                    _SC._listInstance = false
                end
            else
                local function CheckOnWndMsg(instance)

                    for _, param in ipairs(instance._param) do
                        if param.pType == SCRIPT_PARAM_ONKEYTOGGLE and key == param.key and msg == KEY_DOWN then
                            instance[param.var] = not instance[param.var]
                        elseif param.pType == SCRIPT_PARAM_ONKEYDOWN and key == param.key then
                            instance[param.var] = (msg == KEY_DOWN)
                        end
                    end
                    for _, subInstance in ipairs(instance._subInstances) do
                        CheckOnWndMsg(subInstance)
                    end
                end
                for _, instance in ipairs(_SC.instances) do
                    CheckOnWndMsg(instance)
                end
            end
        end

        AddMsgCallback(__SC__OnWndMsg)
    end
end

function scriptConfig:__init(header, name, parent)
	assert((type(header) == "string") and (type(name) == "string"), "scriptConfig: expected <string>, <string>)")
	if not parent then
		__SC__init(name)
		__SC__OnLoad()
	else
		self._parent = parent
	end
	self.header = header
	self.name = name
	self._tsInstances = {}
	self._param = {}
	self._permaShow = {}
	self._subInstances = {}
	self._subMenuIndex = 0
	self._x = parent and (parent._x + _SC.draw.width + _SC.draw.border*2) or _SC._Idraw.x
	self._y = 0
	self._height = 0
	self._slice = false
	table.insert(parent and parent._subInstances or _SC.instances, self)
end

function scriptConfig:addSubMenu(header, name)
	assert((type(header) == "string") and (type(name) == "string"), "scriptConfig: expected <string>, <string>)")
	local subName = self.name .. "_" .. name
	local sub = scriptConfig(header, subName, self)
	self[name] = sub
end

function scriptConfig:addParam(pVar, pText, pType, defaultValue, a, b, c)
	if _G.SV_PERMASHOW ~= nil and self.name ~= nil and (
	   (self.name == "sidasacsetup_sidasacautocarrysub" and pVar == "Active" and pText == "Auto Carry" and pType == 2) -- SAC:R AutoCarry
	or (self.name == "sidasacsetup_sidasacmixedmodesub" and pVar == "Active" and pText == "Mixed Mode" and pType == 2) -- SAC:R MixedMode
	or (self.name == "sidasacsetup_sidasaclasthitsub" and pVar == "Active" and pText == "Last Hit" and pType == 2) -- SAC:R LastHit
	or (self.name == "sidasacsetup_sidasaclaneclearsub" and pVar == "Active" and pText == "Lane Clear" and pType == 2) -- SAC:R LaneClear
	or (string.find(self.name, "sidasacvayne")) -- SAC Vayne Plugin
	or (self.name == "MMA2013" and pVar == "scriptActive" and pText == "Orbwalk OnHold" and pType == 2) -- MMA AutoCarry
	or (self.name == "MMA2013" and pVar == "lastHitting" and pText == "LastHit OnHold" and pType == 2) -- MMA LastHit
	or (self.name == "MMA2013" and pVar == "laneClear" and pText == "LaneClear OnHold" and pType == 2) -- MMA LaneClear
	or (self.name == "MMA2013" and pVar == "hybridMode" and pText == "HybridMode OnHold" and pType == 2) -- MMA AutoCarry
	or (self.name == "sidasacmain" and pVar == "AutoCarry" and pText == "Auto Carry" and pType == 2) -- SAC Revamped AutoCarry
	or (self.name == "sidasacmain" and pVar == "LastHit" and pText == "Last Hit" and pType == 2) -- SAC Revamped LastHit
	or (self.name == "sidasacmain" and pVar == "LaneClear" and pText == "Lane Clear" and pType == 2) -- SAC Revamped LaneClear
	or (self.name == "sidasacmain" and pVar == "MixedMode" and pText == "Mixed Mode" and pType == 2) -- SAC Revamped MixedMode
	or (self.name == "ShadowVayne_sow" and pVar == "Mode1" and pText == "Mixed Mode!" and pType == 2) -- SOW MixedMode
	or (self.name == "ShadowVayne_sow" and pVar == "Mode3" and pText == "Last hit!" and pType == 2) -- SOW MixedMode
	or (self.name == "ShadowVayne_sow" and pVar == "Mode2" and pText == "Laneclear!" and pType == 2) -- SOW MixedMode
	or (self.name == "ShadowVayne_sow" and pVar == "Mode0" and pText == "Carry me!" and pType == 2) -- SOW MixedMode
	) then
--~ 		if (self.name == "MMA2013") then
--~ 			pVar = "BLANKSPACE"
--~ 		end
		pText = "ShadowVayneHijacked"
		pType = SCRIPT_PARAM_INFO
		defaultValue = false
		a = nil
		b = nil
		c = nil
		local newParam = { var = pVar, text = pText, pType = pType }
		self[pVar] = defaultValue
		table.insert(self._param, newParam)
		__SC__saveMaster()
		self:load()
	else
--~ 		if string.find(pVar,"Mode") and self.name == "ShadowVayne_sow" then
--~ 			print((self.name).." "..pVar.." "..pText.." "..pType)
--~ 		end
		assert(type(pVar) == "string" and type(pText) == "string" and type(pType) == "number", "addParam: wrong argument types (<string>, <string>, <pType> expected)")
		assert(string.find(pVar, "[^%a%d]") == nil, "addParam: pVar should contain only char and number")
		--assert(self[pVar] == nil, "addParam: pVar should be unique, already existing " .. pVar)
		local newParam = { var = pVar, text = pText, pType = pType }
		if pType == SCRIPT_PARAM_ONOFF then
			assert(type(defaultValue) == "boolean", "addParam: wrong argument types (<boolean> expected)")
		elseif pType == SCRIPT_PARAM_COLOR then
			assert(type(defaultValue) == "table", "addParam: wrong argument types (<table> expected)")
			assert(#defaultValue == 4, "addParam: wrong argument ({a,r,g,b} expected)")
		elseif pType == SCRIPT_PARAM_ONKEYDOWN or pType == SCRIPT_PARAM_ONKEYTOGGLE then
		   assert(type(defaultValue) == "boolean" and type(a) == "number", "addParam: wrong argument types (<boolean> <number> expected)")
			newParam.key = a
		elseif pType == SCRIPT_PARAM_SLICE then
			assert(type(defaultValue) == "number" and type(a) == "number" and type(b) == "number" and (type(c) == "number" or c == nil), "addParam: wrong argument types (pVar, pText, pType, defaultValue, valMin, valMax, decimal) expected")
			newParam.min = a
			newParam.max = b
			newParam.idc = c or 0
			newParam.cursor = 0
		elseif pType == SCRIPT_PARAM_LIST then
			assert(type(defaultValue) == "number" and type(a) == "table", "addParam: wrong argument types (pVar, pText, pType, defaultValue, listTable) expected")
			newParam.listTable = a
			newParam.min = 1
			newParam.max = #a
			newParam.cursor = 0
		end
		self[pVar] = defaultValue
		table.insert(self._param, newParam)
		__SC__saveMaster()
		self:load()
	end
end

function scriptConfig:addTS(tsInstance)
    assert(type(tsInstance.mode) == "number", "addTS: expected TargetSelector)")
    _SC.useTS = true
    table.insert(self._tsInstances, tsInstance)
    __SC__saveMaster()
    self:load()
end

function scriptConfig:permaShow(pVar)
	assert(type(pVar) == "string" and self[pVar] ~= nil, "permaShow: existing pVar expected)")
	for index, param in ipairs(self._param) do
		if param.var == pVar then
			if not (param.text == "ShadowVayneHijacked") and not (string.find(param.text, "Auto Carry: Reborn")) then
				table.insert(self._permaShow, index)
			end
		end
	end
	__SC__saveMaster()
end

function scriptConfig:_txtKey(key)
	return (key > 32 and key < 96 and " " .. string.char(key) .. " " or "(" .. tostring(key) .. ")")
end

function scriptConfig:OnDraw()
    self._x = self._parent and (self._parent._x + _SC.draw.width + _SC.draw.border*2) or _SC._Idraw.x
    if self._slice and _SC._sliceInstance then
        local cursorX = math.min(math.max(0, GetCursorPos().x - self._x - _SC.draw.row3), _SC.draw.width - _SC.draw.row3)
        self[self._param[self._slice].var] = math.round(self._param[self._slice].min + cursorX / (_SC.draw.width - _SC.draw.row3) * (self._param[self._slice].max - self._param[self._slice].min), self._param[self._slice].idc)
    end
    self._y = _SC.draw.y
    DrawLine(self._x + _SC.draw.width / 2, self._y, self._x + _SC.draw.width / 2, self._y + self._height, _SC.draw.width + _SC.draw.border * 2, 1414812756) -- grey
    local menuText = _SC._changeKey and _SC._changeKeyVar and _SC._changeKeyInstance and _SC._changeKeyInstance.name == self.name and "press key for " .. self._param[_SC._changeKeyVar].var or self.header
    DrawText(menuText, _SC.draw.fontSize, self._x, self._y, 4294967280) -- ivory
    self._y = self._y + _SC.draw.cellSize
    for index, _ in ipairs(self._subInstances) do
        self:_DrawSubInstance(index)
        if self._subMenuIndex == index then _:OnDraw() end
    end

    if #self._tsInstances > 0 then
        --_SC._Idraw.y = TS__DrawMenu(_SC._Idraw.x, _SC._Idraw.y)
        for _, tsInstance in ipairs(self._tsInstances) do
            self._y = tsInstance:DrawMenu(self._x, self._y)
        end
    end
    for index, _ in ipairs(self._param) do
        self:_DrawParam(index)
    end
    self._height = self._y - _SC.draw.y
    if self._list and _SC._listInstance and self._listY then
        local cursorY = math.min(GetCursorPos().y - self._listY, _SC.draw.cellSize * (self._param[self._list].max))
        if cursorY >= 0 then
            self[self._param[self._list].var] = math.round(self._param[self._list].min + cursorY / (_SC.draw.cellSize * (self._param[self._list].max)) * (self._param[self._list].max - self._param[self._list].min))
        end
        local maxWidth = 0
        for i, el in pairs(self._param[self._list].listTable) do
            maxWidth = math.max(maxWidth, GetTextArea(el, _SC.draw.fontSize).x)
        end
        -- BG:
        DrawRectangle(self._x + _SC.draw.row3, self._listY, maxWidth, self._param[self._list].max * _SC.draw.cellSize, ARGB(230,50,50,50))
        -- SELECTED:
        DrawRectangle(self._x + _SC.draw.row3, self._listY + (self[self._param[self._list].var]-1) * _SC.draw.cellSize, maxWidth, _SC.draw.cellSize, _SC.color.green)
        for i, el in pairs(self._param[self._list].listTable) do
            DrawText(el, _SC.draw.fontSize, self._x + _SC.draw.row3, self._listY + (i-1) * _SC.draw.cellSize, 4294967280)
        end
    end
end

function scriptConfig:_DrawSubInstance(index)
    local pVar = self._subInstances[index].name
    local selected = self._subMenuIndex == index
    DrawLine(self._x - _SC.draw.border, self._y + _SC.draw.midSize, self._x + _SC.draw.width + _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, (selected and _SC.color.red or _SC.color.lgrey))
    DrawText(self._subInstances[index].header, _SC.draw.fontSize, self._x, self._y, (selected and _SC.color.ivory or _SC.color.grey))
    DrawText("        >>", _SC.draw.fontSize, self._x + _SC.draw.row3 + _SC.draw.border, self._y, (selected and _SC.color.ivory or _SC.color.grey))
    --_SC._Idraw.y = _SC._Idraw.y + _SC.draw.cellSize
    self._y = self._y + _SC.draw.cellSize
end

function scriptConfig:_DrawParam(varIndex)
    local pVar = self._param[varIndex].var
	if (self._param[varIndex].text == "ShadowVayneHijacked") or (string.find(self._param[varIndex].text, "OnHold:") and _G.SV_PERMASHOW) then
		DrawLine(self._x - _SC.draw.border, self._y + _SC.draw.midSize, self._x + _SC.draw.row3 - _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, _SC.color.lgrey)
		DrawText("ShadowVayne found. Set the Keysettings there!", _SC.draw.fontSize, self._x, self._y, _SC.color.grey)
		self._y = self._y + _SC.draw.cellSize
	else
		DrawLine(self._x - _SC.draw.border, self._y + _SC.draw.midSize, self._x + _SC.draw.row3 - _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, _SC.color.lgrey)
		DrawText(self._param[varIndex].text, _SC.draw.fontSize, self._x, self._y, _SC.color.grey)
		if self._param[varIndex].pType == SCRIPT_PARAM_SLICE then
			DrawText(tostring(self[pVar]), _SC.draw.fontSize, self._x + _SC.draw.row2, self._y, _SC.color.grey)
			DrawLine(self._x + _SC.draw.row3, self._y + _SC.draw.midSize, self._x + _SC.draw.width + _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, _SC.color.lgrey)
			-- cursor
			self._param[varIndex].cursor = (self[pVar] - self._param[varIndex].min) / (self._param[varIndex].max - self._param[varIndex].min) * (_SC.draw.width - _SC.draw.row3)
			DrawLine(self._x + _SC.draw.row3 + self._param[varIndex].cursor - _SC.draw.border, self._y + _SC.draw.midSize, self._x + _SC.draw.row3 + self._param[varIndex].cursor + _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, 4292598640)
		elseif self._param[varIndex].pType == SCRIPT_PARAM_LIST then
			local text = tostring(self._param[varIndex].listTable[self[pVar]])
			local maxWidth = (_SC.draw.width - _SC.draw.row3) * 0.8
			local textWidth = GetTextArea(text, _SC.draw.fontSize).x
			if textWidth > maxWidth then
				text = text:sub(1, math.floor(text:len() * maxWidth / textWidth)) .. ".."
			end
			DrawText(text, _SC.draw.fontSize, self._x + _SC.draw.row3, self._y, _SC.color.grey)
			if self._list and _SC._listInstance then self._listY = self._y + _SC.draw.cellSize end
		elseif self._param[varIndex].pType == SCRIPT_PARAM_INFO then
			DrawText(tostring(self[pVar]), _SC.draw.fontSize, self._x + _SC.draw.row3 + _SC.draw.border, self._y, _SC.color.grey)
		elseif self._param[varIndex].pType == SCRIPT_PARAM_COLOR then
			DrawRectangle(self._x + _SC.draw.row3 + _SC.draw.border, self._y, 80, _SC.draw.cellSize, ARGB(self[pVar][1], self[pVar][2], self[pVar][3], self[pVar][4]))
		else
			if (self._param[varIndex].pType == SCRIPT_PARAM_ONKEYDOWN or self._param[varIndex].pType == SCRIPT_PARAM_ONKEYTOGGLE) then
				DrawText(self:_txtKey(self._param[varIndex].key), _SC.draw.fontSize, self._x + _SC.draw.row2, self._y, _SC.color.grey)
			end
			DrawLine(self._x + _SC.draw.row3, self._y + _SC.draw.midSize, self._x + _SC.draw.width + _SC.draw.border, self._y + _SC.draw.midSize, _SC.draw.cellSize, (self[pVar] and _SC.color.green or _SC.color.lgrey))
			DrawText((self[pVar] and "        ON" or "        OFF"), _SC.draw.fontSize, self._x + _SC.draw.row3 + _SC.draw.border, self._y, _SC.color.grey)
		end
		self._y = self._y + _SC.draw.cellSize
	end
end

function scriptConfig:load()
    local function sensitiveMerge(base, t)
        for i, v in pairs(t) do
            if type(base[i]) == type(v) then
                if type(v) == "table" then sensitiveMerge(base[i], v)
                else base[i] = v
                end
            end
        end
    end

    local config = __SC__load(self.name)
    for var, value in pairs(config) do
       if type(value) == "table" then
            if self[var] then sensitiveMerge(self[var], value) end
        else self[var] = value
        end
    end
end

function scriptConfig:save()
    local content = {}
    content._param = content._param or {}
    for var, param in pairs(self._param) do
        if param.pType ~= SCRIPT_PARAM_INFO then
            content[param.var] = self[param.var]
            if param.pType == SCRIPT_PARAM_ONKEYDOWN or param.pType == SCRIPT_PARAM_ONKEYTOGGLE then
                content._param[var] = { key = param.key }
            end
        end
    end
    content._tsInstances = content._tsInstances or {}
    for i, ts in pairs(self._tsInstances) do
        content._tsInstances[i] = { mode = ts.mode }
    end
    -- for i,pShow in pairs(self._permaShow) do
    -- table.insert (content, "_permaShow."..i.."="..tostring(pShow))
    -- end
    __SC__save(self.name, content)
end

function scriptConfig:ResetSubIndexes()
    if self._subMenuIndex > 0 then
        self._subInstances[self._subMenuIndex]:ResetSubIndexes()
        self._subMenuIndex = 0
    end
end

function scriptConfig:OnWndMsg()
    local y1 = _SC.draw.y + _SC.draw.cellSize
    if CursorIsUnder(self._x, _SC.draw.y, _SC.draw.width + _SC.draw.border, _SC.draw.cellSize) then self:ResetSubIndexes() end
    for i, instance in ipairs(self._subInstances) do
        if CursorIsUnder(self._x, y1, _SC.draw.width + _SC.draw.border, _SC.draw.cellSize) then self._subMenuIndex = i return end
        y1 = y1 + _SC.draw.cellSize
    end
    if #self._tsInstances > 0 then
        for _, tsInstance in ipairs(self._tsInstances) do
            y1 = tsInstance:ClickMenu(self._x, y1)
        end
    end
    for i, param in ipairs(self._param) do
        if param.pType == SCRIPT_PARAM_ONKEYDOWN or param.pType == SCRIPT_PARAM_ONKEYTOGGLE then
            if CursorIsUnder(self._x + _SC.draw.row2, y1, _SC.draw.fontSize, _SC.draw.fontSize) then
                _SC._changeKey, _SC._changeKeyVar, _SC._changeKeyMenu = true, i, true
                _SC._changeKeyInstance = self
                self:ResetSubIndexes()
                return
            end
        end
        if param.pType == SCRIPT_PARAM_ONOFF or param.pType == SCRIPT_PARAM_ONKEYTOGGLE then
            if CursorIsUnder(self._x + _SC.draw.row3, y1, _SC.draw.width - _SC.draw.row3, _SC.draw.fontSize) then
                self[param.var] = not self[param.var]
                self:save()
                self:ResetSubIndexes()
                return
            end
        end
        if param.pType == SCRIPT_PARAM_COLOR then
            if CursorIsUnder(self._x + _SC.draw.row3, y1, _SC.draw.width - _SC.draw.row3, _SC.draw.fontSize) then
                __CP(nil, nil, self[param.var][1], self[param.var][2], self[param.var][3], self[param.var][4], self[param.var])
                self:save()
                self:ResetSubIndexes()
                return
            end
        end
        if param.pType == SCRIPT_PARAM_SLICE then
            if CursorIsUnder(self._x + _SC.draw.row3 - _SC.draw.border, y1, WINDOW_W, _SC.draw.fontSize) then
                self._slice = i
                _SC._sliceInstance = self
                self:ResetSubIndexes()

                return
            end
        end
        if param.pType == SCRIPT_PARAM_LIST then
            if CursorIsUnder(self._x + _SC.draw.row3 - _SC.draw.border, y1, WINDOW_W, _SC.draw.fontSize) then
                self._list = i
                _SC._listInstance = self
                self:ResetSubIndexes()

                return
            end
        end
        y1 = y1 + _SC.draw.cellSize
    end
end